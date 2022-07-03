<?php

namespace Tests\Integration\Services;

use App\Services\DisposableIncomeService;
use Tests\TestCase;

class DisposableIncomeServiceTest extends TestCase
{
    public function testEmpty()
    {
        $service = new DisposableIncomeService();
        $this->assertEmpty($service->getAverageDisposableIncomeInPence([]));
    }

    public function testWithDataWithoutRentAdjustment()
    {
        $statement = json_decode(file_get_contents('tests/Fixtures/statement.json'));
        $service = new DisposableIncomeService();

        $this->assertEquals(66813, $service->getAverageDisposableIncomeInPence($statement));
    }

    public function testWithDataWithoutNoneRepeatingIncome()
    {
        $statement = json_decode(file_get_contents('tests/Fixtures/none_repeating_income.json'));
        $service = new DisposableIncomeService();

        $this->assertEquals(66813, $service->getAverageDisposableIncomeInPence($statement));
    }

    public function testWithDataWithoutNoneRepeatingDebit()
    {
        $statement = json_decode(file_get_contents('tests/Fixtures/none_repeating_income.json'));
        $service = new DisposableIncomeService();

        $this->assertEquals(66813, $service->getAverageDisposableIncomeInPence($statement));
    }

    public function testWithDataWithRentAdjustment()
    {
        $statement = json_decode(file_get_contents('tests/Fixtures/statement.json'));
        $service = new DisposableIncomeService();

        $this->assertEquals(76813, $service->getAverageDisposableIncomeInPence($statement, 10000));
    }
}
