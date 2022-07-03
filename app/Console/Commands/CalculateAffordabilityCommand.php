<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Helpers\MoneyHelper;
use App\Models\Property;
use App\Services\AffordabilityService;
use App\Services\CSV\PropertyCSVFetcher;
use App\Services\CSV\StatementCSVFetcher;
use Illuminate\Console\Command;

class CalculateAffordabilityCommand extends Command
{
    protected $signature = 'calculate:affordability {currentRent?}';

    protected $description = 'Fetches ';

    public function handle(
        AffordabilityService $affordabilityService,
        StatementCSVFetcher $csvFetcher,
        PropertyCSVFetcher $propertyCSVFetcher
    ): void {
        try {
            $rent = $this->argument('currentRent') ?? null;
            if ($rent !== null) {
                $rent = MoneyHelper::convertPoundStringToPence($rent);
            }

            // Get property data from csv
            $properties = $propertyCSVFetcher->fetch('tests/Files/properties.csv') ?? [];
            // Get statement data from CSV
            $statementData = $csvFetcher->fetch('tests/Files/bank_statement.csv') ?? [];

            $affordable = $affordabilityService->getAffordablePropertiesList($properties, $statementData, $rent);

            if (count($affordable) === 0) {
                $this->output->error('Apologies, there are no properties that match your affordability criteria');
                return;
            }
            $this->output->success("Congratulations, The following properties satisfy your affordability criteria:");
            /** @var Property $property */
            foreach ($affordable as $property) {
                $this->output->writeln($property->toString());
            }
        } catch (\Exception $exception) {
            $this->output->error($exception->getMessage());
        }
    }
}
