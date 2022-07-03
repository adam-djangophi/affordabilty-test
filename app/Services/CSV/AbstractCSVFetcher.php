<?php

declare(strict_types=1);

namespace App\Services\CSV;

use Illuminate\Support\Facades\File;

abstract class AbstractCSVFetcher
{
    abstract protected function isSkipScenario(array $data): bool;

    public function fetch(string $filePath): ?array
    {
        $extension = File::extension($filePath);
        if ($extension !== 'csv') {
            return null;
        }

        $result = [];
        if (($handle = fopen($filePath, "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                if ($this->isSkipScenario($data)) {
                    continue;
                }
                $result[] = $data;
            }
            fclose($handle);
        }

        return $result;
    }
}
