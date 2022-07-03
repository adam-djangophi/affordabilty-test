<?php

declare(strict_types=1);

namespace App\Models;

use App\Helpers\MoneyHelper;

class Transaction
{
    private string $date;
    private string $type;
    private string $description;
    private int $amountInInPence;
    private int $amountOutInPence;
    private int $balanceInPence;

    public function __construct(array $transactionData)
    {
        if (!$this->isValid($transactionData)) {
            throw new \Exception('Bad Transaction data');
        }

        $this->date = trim($transactionData[0]);
        $this->type = $transactionData[1];
        $this->description = $transactionData[2];

        $out = trim($transactionData[3]);
        $this->amountOutInPence = $out !== '' ? MoneyHelper::convertPoundStringToPence($transactionData[3]): 0;

        $in = trim($transactionData[4]);
        $this->amountInInPence = $in !== '' ? MoneyHelper::convertPoundStringToPence($transactionData[4]): 0;
        $this->balanceInPence = MoneyHelper::convertPoundStringToPence($transactionData[5]);
    }

    private function isValid(array $transactionData): bool
    {
        if (count($transactionData) !== 6) {
            return false;
        }
        if (trim($transactionData[0]) === '' || count(explode(' ', trim($transactionData[0]))) !== 3) {
            return false;
        }
        if (trim($transactionData[1]) === '' || trim($transactionData[2]) === '') {
            return false;
        }
        if (trim($transactionData[3]) === '' && trim($transactionData[4]) === '') {
            return false;
        }

        return true;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getAmountInInPence(): int
    {
        return $this->amountInInPence;
    }

    public function getAmountOutInPence(): int
    {
        return $this->amountOutInPence;
    }

    public function getBalanceInPence(): int
    {
        return $this->balanceInPence;
    }
}
