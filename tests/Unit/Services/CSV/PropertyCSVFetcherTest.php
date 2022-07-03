<?php

namespace Tests\Unit\Services\CSV;

use App\Services\CSV\PropertyCSVFetcher;
use Tests\TestCase;

class PropertyCSVFetcherTest extends TestCase
{
    public function testFetch(): void
    {
        $propertyCSVFetcher = new PropertyCSVFetcher();
        $properties  = $propertyCSVFetcher->fetch('tests/Files/properties.csv');
        $this->assertCount(11, $properties);
    }

    public function testEmpty(): void
    {
        $propertyCSVFetcher = new PropertyCSVFetcher();
        $properties  = $propertyCSVFetcher->fetch('tests/Files/empty.csv');
        $this->assertCount(0, $properties);
    }

    public function testNotCSV(): void
    {
        $propertyCSVFetcher = new PropertyCSVFetcher();
        $properties  = $propertyCSVFetcher->fetch('tests/Files/properties.pdf');
        $this->assertNull($properties);
    }
}
