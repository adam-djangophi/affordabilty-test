<?php

namespace Tests\Unit\Services\CSV;

use App\Services\CSV\StatementCSVFetcher;
use Tests\TestCase;

class StatementCSVFetcherTest extends TestCase
{
    public function testFetch(): void
    {
        $propertyCSVFetcher = new StatementCSVFetcher();
        $entries  = $propertyCSVFetcher->fetch('tests/Files/bank_statement.csv');
        $this->assertCount(65, $entries);
    }

    public function testEmpty(): void
    {
        $propertyCSVFetcher = new StatementCSVFetcher();
        $properties  = $propertyCSVFetcher->fetch('tests/Files/empty.csv');
        $this->assertCount(0, $properties);
    }

    public function testNotCSV(): void
    {
        $propertyCSVFetcher = new StatementCSVFetcher();
        $properties  = $propertyCSVFetcher->fetch('tests/Files/bank_statement.pdf');
        $this->assertNull($properties);
    }
}
