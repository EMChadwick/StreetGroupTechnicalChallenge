<?php

namespace App\Http\Controllers;

use App\Http\Requests\TenantUploadRequest;
use App\Services\HomeownerParser;
use Spatie\SimpleExcel\SimpleExcelReader;

class TenantUploadController extends Controller
{
    protected HomeownerParser $parser;

    public function __construct()
    {
        $this->parser = app(HomeownerParser::class);
    }

    /**
     * Accept a CSV file upload and return it in processed JSON format.
     */
    public function upload(TenantUploadRequest $request)
    {
        $file = $request->file('csv_file');

        $path = $file->getRealPath();

        $rows = SimpleExcelReader::create($path, 'csv')
            ->getRows()
            ->map(function ($row) {
                // Remove columns where the header is empty
                return array_filter($row, function ($value, $key) {
                    return $key !== '';
                }, ARRAY_FILTER_USE_BOTH);
            })
            ->toArray();

        $jsonData = [];

        foreach ($rows as $line) {
            $discoveredTenants = $this->parser->parseEntry($line['homeowner']);
            foreach ($discoveredTenants as $newTenant) {
                $jsonData[] = $newTenant;
            }
        }

        return response()->json([
            'message' => 'CSV processed successfully.',
            'data' => $jsonData,
        ]);
    }
}
