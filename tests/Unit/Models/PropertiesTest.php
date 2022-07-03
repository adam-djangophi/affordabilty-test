<?php

namespace Tests\Unit\Models;

use App\Models\Properties;
use Tests\TestCase;

class PropertiesTest extends TestCase
{
    public function testInvalidPropertiesThrowsException(): void
    {
        $properties = [['p1', 'p2']];
        $error = false;
        try {
            new Properties($properties);
        } catch (\Exception $exception) {
            $error = true;
        }

        $this->assertTrue($error);

        $properties = [['p1', 'p2', 'p3', 'p4']];
        $error = false;
        try {
            new Properties($properties);
        } catch (\Exception $exception) {
            $error = true;
        }

        $this->assertTrue($error);
    }

    public function testSuccessfulCreation(): void
    {
        $propertyList = [
            [123, '123 address street, city', '675'],
            [456, '456 address street, city', '100']
        ];
        $properties = new Properties($propertyList);
        $this->assertInstanceOf(Properties::class, $properties);
        $this->assertCount(2, $properties->getProperties());
    }
}
