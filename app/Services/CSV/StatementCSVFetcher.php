<?php

declare(strict_types=1);

namespace App\Services\CSV;

class StatementCSVFetcher extends AbstractCSVFetcher
{
    protected function isSkipScenario(array $data): bool
    {
        $num = count($data);
        $ignoreBefore = 'STATEMENT OPENING BALANCE';

        return in_array($ignoreBefore, $data) || $num < 6 || trim($data[5]) === '' || $data[5] === 'Balance';
    }
}
