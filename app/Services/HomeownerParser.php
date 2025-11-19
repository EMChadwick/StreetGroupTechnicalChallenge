<?php

namespace App\Services;

use App\Enums\Title;

class HomeownerParser
{
    private string $singleNamePattern;

    private string $jointNamePattern;

    private string $titlePattern;

    private array $blankTennantEntry;

    public function __construct()
    {
        $titles = Title::values();

        $this->titlePattern = implode('|', $titles);

        $this->jointNamePattern = '/\b('.$this->titlePattern.')\b\s*(?:&|and)\s*\b('.$this->titlePattern.')\b/i';

        $this->singleNamePattern = "/\b(".$this->titlePattern.")\b\s*[\w'-.]+\s*\b\w+\b/i";
        $this->blankTenantEntry = [
            'title' => null,
            'first_name' => null,
            'last_name' => null,
            'initial' => null,
        ];
    }

    public function parseEntry(string $entry): array
    {
        if (preg_match($this->jointNamePattern, $entry)) {
            // handle joint names
            $tenants = $this->parseJoint($entry);
        } else {
            // handle 1 or more separate names
            $tenants = $this->parseIndividual($entry);
        }

        return $tenants;
    }

    protected function parseJoint(string $line): array
    {
        $couple = [
            $this->blankTenantEntry,
            $this->blankTenantEntry,
        ];
        // get the titles (should only be 2) and use them for the tenants' titles
        preg_match_all("/\b(".$this->titlePattern.")\b/i", $line, $matches);
        $titleList = $matches[0] ?? [];
        $couple[0]['title'] = $titleList[0] ?? null;
        $couple[1]['title'] = $titleList[1] ?? null;

        // get the last name (and first for 'mr & mrs x y')
        $name = preg_replace($this->jointNamePattern, '', $line);

        // get the surname and add to the tenants
        $namePieces = array_filter(explode(' ', $name));
        $lastName = end($namePieces);
        $couple[0]['last_name'] = $lastName;
        $couple[1]['last_name'] = $lastName;

        // detect 'mr and mrs x y'
        if (count($namePieces) > 1) {
            // for this naming convention, we assume that the first title is the one whose full name is used.
            $couple[0]['first_name'] = $namePieces[1];
        }

        return $couple;
    }

    protected function parseIndividual(string $line): array
    {
        $tenantData = [];
        preg_match_all($this->singleNamePattern, $line, $matches);
        $tenantList = $matches[0];

        foreach ($tenantList as $tenant) {

            $tenantItem = $this->blankTenantEntry;
            $tenantPieces = explode(' ', $tenant);
            $tenantItem['title'] = $tenantPieces[0];
            $first = trim($tenantPieces[1], '.');

            if (strlen($first) == 1) {
                $tenantItem['initial'] = $first;
            } else {
                $tenantItem['first_name'] = $first;
            }
            $tenantItem['last_name'] = end($tenantPieces);
            $tenantData[] = $tenantItem;
        }

        return $tenantData;
    }
}
