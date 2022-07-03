<?php

namespace Tests\Integration\Commmand;

use App\Models\Properties;
use App\Services\AffordabilityService;
use App\Services\CSV\AbstractCSVFetcher;
use App\Services\CSV\PropertyCSVFetcher;
use App\Services\CSV\StatementCSVFetcher;
use Tests\TestCase;

class CalculateAffordabilityCommandTest extends TestCase
{
    public function testCommandCanNotAfford(): void
    {
        $this->app->instance(
            AffordabilityService::class,
            \Mockery::mock(AffordabilityService::class, function ($mock) {
                $mock->shouldReceive('getAffordablePropertiesList')->andReturn([]);
            })
        );

        $this->app->instance(
            AbstractCSVFetcher::class,
            \Mockery::mock(StatementCSVFetcher::class, function ($mock) {
                $mock->shouldReceive('fetch')->andReturn([]);
            })
        );

        $this->app->instance(
            AbstractCSVFetcher::class,
            \Mockery::mock(PropertyCSVFetcher::class, function ($mock) {
                $mock->shouldReceive('fetch')->andReturn([]);
            })
        );

        $this->artisan('calculate:affordability')
            ->expectsOutputToContain('there are no properties');
    }

    public function testCommandCanAfford(): void
    {
        $this->app->instance(
            AbstractCSVFetcher::class,
            \Mockery::mock(StatementCSVFetcher::class, function ($mock) {
                $mock->shouldReceive('fetch')->andReturn(
                    json_decode(file_get_contents('tests/Fixtures/statement.json'))
                );
            })
        );

        $this->app->instance(
            AbstractCSVFetcher::class,
            \Mockery::mock(PropertyCSVFetcher::class, function ($mock) {
                $mock->shouldReceive('fetch')->andReturn(
                    json_decode(file_get_contents('tests/Fixtures/properties.json'))
                );
            })
        );

        $this->artisan('calculate:affordability')
            ->expectsOutputToContain('Congratulations')
            ->expectsOutputToContain('99  Brackley Road,')
            ->expectsOutputToContain('103  Ploughley Rd')
            ->expectsOutputToContain('89  Russell Rd')
            ->expectsOutputToContain('45  Ockham Road')
            ->expectsOutputToContain('65  Guildry Street')
            ->expectsOutputToContain('55  Trinity Crescent')
        ;
    }

    public function testCommandCanAffordMoreWithRentAdjustment(): void
    {
        $this->app->instance(
            AbstractCSVFetcher::class,
            \Mockery::mock(StatementCSVFetcher::class, function ($mock) {
                $mock->shouldReceive('fetch')->andReturn(
                    json_decode(file_get_contents('tests/Fixtures/statement.json'))
                );
            })
        );

        $this->app->instance(
            AbstractCSVFetcher::class,
            \Mockery::mock(PropertyCSVFetcher::class, function ($mock) {
                $mock->shouldReceive('fetch')->andReturn(
                    json_decode(file_get_contents('tests/Fixtures/properties.json'))
                );
            })
        );

        $this->artisan('calculate:affordability 1000')
            ->expectsOutputToContain('Congratulations')
            ->expectsOutputToContain('99  Brackley Road,')
            ->expectsOutputToContain('103  Ploughley Rd')
            ->expectsOutputToContain('89  Russell Rd')
            ->expectsOutputToContain('45  Ockham Road')
            ->expectsOutputToContain('65  Guildry Street')
            ->expectsOutputToContain('55  Trinity Crescent')
            ->expectsOutputToContain('103  Thames Street')
            ->expectsOutputToContain('34  Broomfield Place')
            ->expectsOutputToContain('78  Terrick Rd')
            ->expectsOutputToContain('33  Hounslow Rd')
            ->expectsOutputToContain('116  Chapel Lane')
        ;
    }

    public function testException(): void
    {
        $this->app->instance(
            AffordabilityService::class,
            \Mockery::mock(AffordabilityService::class, function ($mock) {
                $mock->shouldReceive('getAffordablePropertiesList')->andThrow(new \Exception('fault'));
            })
        );

        $this->app->instance(
            AbstractCSVFetcher::class,
            \Mockery::mock(StatementCSVFetcher::class, function ($mock) {
                $mock->shouldReceive('fetch')->andReturn([]);
            })
        );

        $this->app->instance(
            AbstractCSVFetcher::class,
            \Mockery::mock(PropertyCSVFetcher::class, function ($mock) {
                $mock->shouldReceive('fetch')
                    ->andReturn([]);
            })
        );

        $this->artisan('calculate:affordability')
            ->expectsOutputToContain('fault');
    }
}
