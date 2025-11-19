<?php

namespace Tests\Unit;

use App\Services\HomeownerParser;
use PHPUnit\Framework\TestCase;

class TenantParseTest extends TestCase
{
    protected HomeownerParser $parser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = app(HomeownerParser::class);
    }

    /**
     * A basic unit test example.
     */
    public function test_single_name_processes_correctly(): void
    {
        $result = $this->parser->parseEntry('Mr John Smith');

        $expectedResult = [
            [
                'title' => 'Mr',
                'first_name' => 'John',
                'last_name' => 'Smith',
                'initial' => null,
            ],
        ];

        $this->assertEquals($result, $expectedResult);
    }

    public function test_initialed_name_processes_correctly(): void
    {
        $result = $this->parser->parseEntry('Ms K Mulgrew');

        $expectedResult = [
            [
                'title' => 'Ms',
                'first_name' => null,
                'last_name' => 'Mulgrew',
                'initial' => 'K',
            ],
        ];

        $this->assertEquals($result, $expectedResult);
    }

    public function test_hyphenated_name_processes_correctly(): void
    {
        $result = $this->parser->parseEntry('Mr Daniel Day-Lewis');
        $expectedResult = [
            [
                'title' => 'Mr',
                'first_name' => 'Daniel',
                'last_name' => 'Day-Lewis',
                'initial' => null,
            ],
        ];

        $this->assertEquals($result, $expectedResult);
    }

    public function test_joint_name_processes_correctly(): void
    {
        $result = $this->parser->parseEntry('Mr and Mrs Smith');

        $expectedResult = [
            [
                'title' => 'Mr',
                'first_name' => null,
                'last_name' => 'Smith',
                'initial' => null,
            ],
            [
                'title' => 'Mrs',
                'first_name' => null,
                'last_name' => 'Smith',
                'initial' => null,
            ],
        ];

        $this->assertEquals($result, $expectedResult);
    }

    public function test_multiple_full_names_process_correctly(): void
    {
        $result = $this->parser->parseEntry('Dr Gabriella Boudreau and Mr Andrew Ryan');

        $expectedResult = [
            [
                'title' => 'Dr',
                'first_name' => 'Gabriella',
                'last_name' => 'Boudreau',
                'initial' => null,
            ],
            [
                'title' => 'Mr',
                'first_name' => 'Andrew',
                'last_name' => 'Ryan',
                'initial' => null,
            ],
        ];

        $this->assertEquals($result, $expectedResult);
    }

    public function test_joint_full_name_processes_correctly(): void
    {
        $result = $this->parser->parseEntry('Mr and Mrs Gavin Stacey');

        $expectedResult = [
            [
                'title' => 'Mr',
                'first_name' => 'Gavin',
                'last_name' => 'Stacey',
                'initial' => null,
            ],
            [
                'title' => 'Mrs',
                'first_name' => null,
                'last_name' => 'Stacey',
                'initial' => null,
            ],
        ];

        $this->assertEquals($result, $expectedResult);
    }
}
