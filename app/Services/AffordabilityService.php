<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Properties;
use App\Models\Property;

class AffordabilityService
{
    public function __construct(private DisposableIncomeService $disposableIncomeService)
    {
    }

    public function getAffordablePropertiesList(array $propertyData, array $statementData, ?int $rent = null): array
    {
        $properties = new Properties($propertyData);
        $disposableIncome = $this->disposableIncomeService->getAverageDisposableIncomeInPence($statementData, $rent);

        $affordable = [];
        /** @var Property $property */
        foreach ($properties->getProperties() as $property) {
            if ($disposableIncome > $property->getAffordabilityPriceInPence()) {
                $affordable[] = $property;
            }
        }

        return $affordable;
    }
}
