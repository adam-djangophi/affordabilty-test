<?php

declare(strict_types=1);

namespace App\Models;

use App\Helpers\MoneyHelper;

class Property
{
    private int $id;
    private string $address;
    private int $priceInPence;
    private float $affordabilityPriceInPence;

    public function __construct(array $property)
    {
        if (count($property) != 3) {
            throw new \Exception('Bad property data');
        }

        $this->id = (int) $property[0];
        $this->address = (string) $property[1];
        $this->priceInPence = MoneyHelper::convertPoundStringToPence($property[2]);
        $this->affordabilityPriceInPence = $this->priceInPence * 1.25;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getPriceInPence(): int
    {
        return $this->priceInPence;
    }

    public function getAffordabilityPriceInPence(): float
    {
        return $this->affordabilityPriceInPence;
    }

    public function toString(): string
    {
        return "Address: {$this->address} Price: " . MoneyHelper::convertPenceToPounds($this->priceInPence);
    }
}
