<?php

namespace Tests\Integration\Services;

use App\Services\AffordabilityService;
use App\Services\DisposableIncomeService;
use Mockery\MockInterface;
use Tests\TestCase;

class AffordabilityServiceTest extends TestCase
{
    public function testWhenEmpty(): void
    {
        $properties = [];
        $affordabilityMock = \Mockery::mock(DisposableIncomeService::class, function (MockInterface $mock) {
            $mock->shouldReceive('getAverageDisposableIncomeInPence')->andReturn(12300);
        });
        $service = new AffordabilityService($affordabilityMock);

        $result = $service->getAffordablePropertiesList($properties, []);
        $this->assertEmpty($result);
    }

    public function testNoAffordableProperties(): void
    {
        $properties = [
            [123, 'address', '£654.00'],
            [456, 'address1', '£700.00'],
            [798, 'address2', '£1000.00']
        ];

        $affordabilityMock = \Mockery::mock(DisposableIncomeService::class, function (MockInterface $mock) {
            $mock->shouldReceive('getAverageDisposableIncomeInPence')->andReturn(12300);
        });
        $service = new AffordabilityService($affordabilityMock);
        $this->assertEmpty($service->getAffordablePropertiesList($properties, []));

        $affordabilityMock = \Mockery::mock(DisposableIncomeService::class, function (MockInterface $mock) {
            $mock->shouldReceive('getAverageDisposableIncomeInPence')->andReturn(55400);
        });
        $service = new AffordabilityService($affordabilityMock);
        $this->assertEmpty($service->getAffordablePropertiesList($properties, []));

        $affordabilityMock = \Mockery::mock(DisposableIncomeService::class, function (MockInterface $mock) {
            $mock->shouldReceive('getAverageDisposableIncomeInPence')->andReturn(65400);
        });
        $service = new AffordabilityService($affordabilityMock);
        $this->assertEmpty($service->getAffordablePropertiesList($properties, []));
    }

    public function testAffordablePropertiesReturned(): void
    {
        $affordabilityMock = \Mockery::mock(DisposableIncomeService::class, function (MockInterface $mock) {
            $mock->shouldReceive('getAverageDisposableIncomeInPence')->andReturn(99000);
        });
        $properties =
            [
                [123, 'address', '£654.00'],
                [456, 'address1', '£700.00'],
                [798, 'address2', '£1000.00']
            ]
        ;
        $service = new AffordabilityService($affordabilityMock);
        $this->assertCount(2, $service->getAffordablePropertiesList($properties, []));
    }
}
