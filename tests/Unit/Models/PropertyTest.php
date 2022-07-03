<?php

namespace Tests\Unit\Models;

use App\Models\Property;
use Tests\TestCase;

class PropertyTest extends TestCase
{
    public function testInvalidPropertyThrowsException(): void
    {
        $error = false;
        try {
            new Property(['p1', 'p2']);
        } catch (\Exception $exception) {
            $error = true;
        }

        $this->assertTrue($error);

        $error = false;
        try {
            new Property(['p1', 'p2', 'p3', 'p4']);
        } catch (\Exception $exception) {
            $error = true;
        }

        $this->assertTrue($error);
    }

    public function testSuccessfulCreation(): void
    {
        $property =  new Property([123, '123 address street, city', '675']);
        $this->assertInstanceOf(Property::class, $property);
        $this->assertEquals(123, $property->getId());
        $this->assertEquals('123 address street, city', $property->getAddress());
        $this->assertEquals(67500, $property->getPriceInPence());
        $this->assertEquals(84375.0, $property->getAffordabilityPriceInPence());
        $this->assertEquals('Address: 123 address street, city Price: £675.00', $property->toString());
    }
}
