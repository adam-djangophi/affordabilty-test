<?php

declare(strict_types=1);

namespace Tests\Unit\Helpers;

use App\Helpers\MoneyHelper;
use Tests\TestCase;

class MoneyHelperTest extends TestCase
{
    public function testConvertPoundStringToPence(): void
    {
        $cases = [
            '£100,000.56' => 10000056,
            '100,000.56' => 10000056,
            '100000.56' => 10000056,
            '0.56' => 56
        ];

        foreach ($cases as $string => $int) {
            $this->assertEquals($int, MoneyHelper::convertPoundStringToPence($string));
        }
    }

    public function testConvertPenceToPounds(): void
    {
        $cases = [
            10000056 => '£100,000.56',
            56 => '£0.56'
        ];

        foreach ($cases as $int => $string) {
            $this->assertEquals($string, MoneyHelper::convertPenceToPounds($int));
        }
    }
}
