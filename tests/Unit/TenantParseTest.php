<?php

namespace Tests\Unit;

use App\Services\HomeownerParser;
use App\Enums\Title;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class TenantParseTest extends TestCase
{
    protected HomeownerParser $parser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new HomeownerParser(Title::values());
    }


    #[Test]
    #[DataProvider('homeownerCases')]
    public function test_parser_can_handle_examples(string $input, array $expected): void
    {
        $result = $this->parser->parseEntry($input);
        $this->assertEquals($expected, $result);
    }

    public static function homeownerCases(): array
    {
        return [
            ["Mr John Smith", [
                ['title'=>'Mr', 'first_name'=>'John','last_name'=>'Smith','initial'=>null]
            ]],
            ["Ms K Mulgrew", [
                ['title'=>'Ms','first_name'=>null,'last_name'=>'Mulgrew','initial'=>'K'],
            ]],
            ["Mr Daniel Day-Lewis", [
                ['title'=>'Mr','first_name'=>'Daniel','last_name'=>'Day-Lewis','initial'=>null]
            ]],
            ["Mr and Mrs Smith", [
                ['title'=>'Mr','first_name'=>null,'last_name'=>'Smith','initial'=>null],
                ['title'=>'Mrs','first_name'=>null,'last_name'=>'Smith','initial'=>null]
            ]],
            ["Dr Gabriella Boudreau and Mr Andrew Ryan", [
                ['title'=>'Dr','first_name'=>'Gabriella','last_name'=>'Boudreau','initial'=>null],
                ['title'=>'Mr','first_name'=>'Andrew','last_name'=>'Ryan','initial'=>null]
            ]],
            ["Mr and Mrs Gavin Stacey", [
                ['title'=>'Mr','first_name'=>'Gavin','last_name'=>'Stacey','initial'=>null],
                ['title'=>'Mrs','first_name'=>null,'last_name'=>'Stacey','initial'=>null]
            ]],
        ];
    }
}