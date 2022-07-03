<?php

declare(strict_types=1);

namespace App\Models;

class Properties
{
    private array $properties = [];

    public function __construct(array $properties)
    {
        foreach ($properties as $property) {
            $this->properties[] = new Property($property);
        }
    }

    public function getProperties(): array
    {
        return $this->properties;
    }
}
