<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Transaction;

class DisposableIncomeService
{
    private const INCOMING_TYPES = ['Bank Credit'];
    private const OUTGOING_TYPES = ['Direct Debit', 'Standing Order'];

    public function getAverageDisposableIncomeInPence(array $statementData, ?int $currentRentInPence = null): int
    {
        $months = $this->organiseTransactionByMonth($statementData);
        $recurringIncome = $this->getRecurringTransactions($months);
        $recurringExpenditure = $this->getRecurringTransactions($months, false);

        $monthlyDisposable = [];
        foreach ($recurringIncome as $month => $income) {
             $monthlyDisposable[$month] =
                 $income + (array_key_exists($month, $recurringExpenditure) ? $recurringExpenditure[$month]: 0)
             ;
        }

        $count = count($monthlyDisposable);

        return ($count > 0 ? array_sum($monthlyDisposable) / $count : 0) + $currentRentInPence ?: 0;
    }

    private function getRecurringTransactions(array $months, bool $credits = true): array
    {
        $activityByMonth = [];
        $relevantTransactionTypes = $credits ? self::INCOMING_TYPES : self::OUTGOING_TYPES;

        foreach ($months as $monthName => $transactions) {
            $activityByMonth[$monthName] = 0;
            /** @var Transaction $transaction */
            foreach ($transactions as $transaction) {
                if (in_array($transaction->getType(), $relevantTransactionTypes)) {
                    $checkFor = [
                        'type' => $transaction->getType(),
                        'description' => $transaction->getDescription()
                    ];
                    if ($this->checkStatementForOccurence($checkFor, $months, $monthName)) {
                        if ($credits) {
                            $activityByMonth[$monthName] += $transaction->getAmountInInPence();
                        } else {
                            $activityByMonth[$monthName] -= $transaction->getAmountOutInPence();
                        }
                    }
                }
            }
        }

        return $activityByMonth;
    }

    private function checkStatementForOccurence(array $checkFor, array $months, string $currentMonth): bool
    {
        foreach ($months as $monthName => $transactions) {
            if ($monthName === $currentMonth) {
                continue;
            }
            /** @var Transaction $transaction */
            foreach ($transactions as $transaction) {
                if ($transaction->getType() === $checkFor['type'] &&
                    $transaction->getDescription() === $checkFor['description']
                ) {
                    return true;
                }
            }
        }

        return false;
    }

    private function organiseTransactionByMonth(array $statementData): array
    {
        $months = [];
        foreach ($statementData as $transaction) {
            $dateParts = explode(' ', $transaction[0]);
            $monthYear = "{$dateParts[1]}-{$dateParts[2]}";


            $months[$monthYear][] = new Transaction($transaction);
        }

        return $months;
    }
}
