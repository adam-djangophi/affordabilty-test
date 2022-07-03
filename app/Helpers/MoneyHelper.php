<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Str;

class MoneyHelper
{
    public static function convertPoundStringToPence(string $amount): int
    {
        return (int) ((Str::replace(['£', ','], '', $amount)) * 100);
    }

    public static function convertPenceToPounds(int $pence): string
    {
        return '£'. number_format($pence/100, 2);
    }
}
