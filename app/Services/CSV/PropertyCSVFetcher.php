<?php

declare(strict_types=1);

namespace App\Services\CSV;

class PropertyCSVFetcher extends AbstractCSVFetcher
{
    protected function isSkipScenario(array $data): bool
    {
        return in_array('Price (pcm)', $data);
    }
}
