<?php

namespace Tests\Unit\Models;

use App\Models\Transaction;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    public function testFailOnWrongSize(): void
    {
        $this->assertFailCreationCheck(['p1', 'p2']);
        $this->assertFailCreationCheck(['p1', 'p2', 'p3', 'p4', 'p5', 'p6', 'p7']);
    }

    public function testFailOnBadDate(): void
    {
        $this->assertFailCreationCheck([
            '23rd January 2022 some other thing',
            'Direct Debit',
            'Phone bill',
            '£32.45',
            '',
            '£1045,00'
        ]);
        $this->assertFailCreationCheck([
            '  ',
            'Direct Debit',
            'Phone bill',
            '£32.45',
            '',
            '£1045,00'
        ]);
    }

    public function testMissingType(): void
    {
        $this->assertFailCreationCheck([
            '23rd January 2022',
            '',
            'Phone bill',
            '£32.45',
            '',
            '£1045,00'
        ]);
    }

    public function testMissingDescription(): void
    {
        $this->assertFailCreationCheck([
            '23rd January 2022',
            'Direct Debit',
            '',
            '£32.45',
            '',
            '£1045,00'
        ]);
    }

    public function testMissingAmounts(): void
    {
        $this->assertFailCreationCheck([
            '23rd January 2022',
            'Direct Debit',
            'Phone Bill',
            '',
            '',
            '£1045,00'
        ]);
    }

    public function testSuccessfulDebit(): void
    {
        $transaction = new Transaction([
            '23rd January 2022',
            'Direct Debit',
            'Phone bill',
            '£32.45',
            '',
            '£1,000.00'
        ]);
        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertEquals('23rd January 2022', $transaction->getDate());
        $this->assertEquals('Direct Debit', $transaction->getType());
        $this->assertEquals('Phone bill', $transaction->getDescription());
        $this->assertEquals(3245, $transaction->getAmountOutInPence());
        $this->assertEquals(0, $transaction->getAmountInInPence());
        $this->assertEquals(100000, $transaction->getBalanceInPence());
    }

    public function testSuccessfulCredit(): void
    {
        $transaction = new Transaction([
            '23rd January 2022',
            'Bank Credit',
            'Salary',
            '',
            '£1,000.00',
            '£2,000.00'
        ]);
        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertEquals('23rd January 2022', $transaction->getDate());
        $this->assertEquals('Bank Credit', $transaction->getType());
        $this->assertEquals('Salary', $transaction->getDescription());
        $this->assertEquals(0, $transaction->getAmountOutInPence());
        $this->assertEquals(100000, $transaction->getAmountInInPence());
        $this->assertEquals(200000, $transaction->getBalanceInPence());
    }

    private function assertFailCreationCheck(array $transactionData): void
    {
        $error = false;
        try {
            new Transaction($transactionData);
        } catch (\Exception $exception) {
            $error = true;
        }

        $this->assertTrue($error);
    }
}
