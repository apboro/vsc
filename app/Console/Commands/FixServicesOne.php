<?php

namespace App\Console\Commands;

use App\Models\Dictionaries\Contracts;
use App\Models\Dictionaries\Letters;
use App\Models\Dictionaries\PatternLetters;
use App\Models\Dictionaries\ServiceCategories;
use App\Models\Dictionaries\ServiceTypes;
use App\Models\Services\Service;
use App\Models\Services\ServiceProgram;
use Illuminate\Console\Command;

class FixServicesOne extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:services_01';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix services contracts, templates, programs';

    /**
     * Execute the console command.
     *
     * @return int|null
     */
    public function handle(): void
    {
        $contract = Contracts::queryRaw()->where('id', Contracts::standard_one)->where('organization_id', 1)->first();
        if ($contract === null) {
            $contract = new Contracts();
            $contract->id = Contracts::standard_one;
        }
        $contract->name = 'Стандартный';
        $contract->pattern_id = PatternLetters::regular;
        $contract->organization_id = 1;
        $contract->save();

        $contract = Contracts::queryRaw()->where('id', Contracts::standard_two)->where('organization_id', 2)->first();
        if ($contract === null) {
            $contract = new Contracts();
            $contract->id = Contracts::standard_two;
        }
        $contract->name = 'Стандартный';
        $contract->pattern_id = PatternLetters::regular;
        $contract->organization_id = 2;
        $contract->save();

        $services = Service::query()->whereNull('contract_id')->get();
        foreach ($services as $service) {
            /** @var Service $service */
            $service->contract_id = $service->organization_id === 1 ? Contracts::standard_one : Contracts::standard_two;
            $service->save();
        }

        $letter = Letters::queryRaw()->where('id', Letters::standard_one)->where('organization_id', 1)->first();
        if ($letter === null) {
            $letter = new Letters();
            $letter->id = Letters::standard_one;
        }
        $letter->name = 'Регулярный';
        $letter->pattern_id = PatternLetters::regular;
        $letter->organization_id = 1;
        $letter->save();

        $letter = Letters::queryRaw()->where('id', Letters::standard_two)->where('organization_id', 2)->first();
        if ($letter === null) {
            $letter = new Letters();
            $letter->id = Letters::standard_two;
        }
        $letter->name = 'Регулярный';
        $letter->pattern_id = PatternLetters::regular;
        $letter->organization_id = 2;
        $letter->save();

        $services = Service::query()->whereNull('letter_id')->get();
        foreach ($services as $service) {
            /** @var Service $service */
            $service->letter_id = $service->organization_id === 1 ? Letters::standard_one : Letters::standard_two;
            $service->save();
        }

        $program = ServiceProgram::queryRaw()->where('id', 1)->where('organization_id', 1)->first();
        if ($program === null) {
            $program = new ServiceProgram();
            $program->id = 1;
            $program->name = 'Регулярная платная';
            $program->service_type_id = ServiceTypes::regular;
            $program->service_category_id = ServiceCategories::paid;
            $program->organization_id = 1;
            $program->save();
        }
        $services = Service::query()->where('organization_id', 1)->whereNull('type_program_id')->get();
        foreach ($services as $service) {
            /** @var Service $service */
            $service->type_program_id = $program->id;
            $service->save();
        }

        $program = ServiceProgram::queryRaw()->where('id', 2)->where('organization_id', 2)->first();
        if ($program === null) {
            $program = new ServiceProgram();
            $program->id = 2;
            $program->name = 'Регулярная платная';
            $program->service_type_id = ServiceTypes::regular;
            $program->service_category_id = ServiceCategories::paid;
            $program->organization_id = 2;
            $program->save();
        }
        $services = Service::query()->where('organization_id', 2)->whereNull('type_program_id')->get();
        foreach ($services as $service) {
            /** @var Service $service */
            $service->type_program_id = $program->id;
            $service->save();
        }
    }
}
