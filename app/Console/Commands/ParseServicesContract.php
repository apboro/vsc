<?php

namespace App\Console\Commands;

use App\Models\Dictionaries\Contracts;
use App\Models\Services\Service;
use Illuminate\Console\Command;

class ParseServicesContract extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:services-contract';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse contract lines this services';

    /**
     * Execute the console command.
     *
     * @return int|null
     */
    public function handle(): void
    {
        $services = Service::query()->whereNull('contract_id')->get();

        if (count($services)) {
            foreach ($services as $service) {
                $items[] = [
                    'id' => $service->id,
                    'contract_id' => $service->organization_id === 1 ? Contracts::standard_one : Contracts::standard_two,
                ];
            }

            Service::upsert($items, 'id', ['contract_id']);
        }
    }
}
