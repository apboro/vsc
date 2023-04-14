<?php

namespace App\Console\Commands;

use App\Models\Dictionaries\Letters;
use App\Models\Services\Service;
use Illuminate\Console\Command;

class ParseServicesLetter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:services-letters';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse letters lines this services';

    /**
     * Execute the console command.
     *
     * @return int|null
     */
    public function handle(): void
    {
        $services = Service::query()->whereNull('letter_id')->get();

        if (count($services)) {
            foreach ($services as $service) {
                $items[] = [
                    'id' => $service->id,
                    'letter_id' => $service->organization_id === 1 ? Letters::standard_one : Letters::standard_two,
                ];
            }

            Service::upsert($items, 'id', ['letter_id']);
        }
    }
}
