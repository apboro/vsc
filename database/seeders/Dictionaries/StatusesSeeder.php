<?php

namespace Database\Seeders\Dictionaries;

use App\Models\Dictionaries\OrganizationStatus;
use App\Models\Dictionaries\PositionStatus;
use App\Models\Dictionaries\TrainingBaseContractStatus;
use App\Models\Dictionaries\TrainingBaseStatus;
use App\Models\Dictionaries\UserStatus;
use Database\Seeders\GenericSeeder;

class StatusesSeeder extends GenericSeeder
{
    protected array $data = [
        UserStatus::class => [
            UserStatus::active => ['name' => 'Действующий'],
            UserStatus::blocked => ['name' => 'Недействующий'],
        ],
        PositionStatus::class => [
            PositionStatus::active => ['name' => 'Действующий'],
            PositionStatus::blocked => ['name' => 'Недействующий'],
        ],
        TrainingBaseStatus::class => [
            TrainingBaseStatus::enabled => ['name' => 'Действующий'],
            TrainingBaseStatus::disabled => ['name' => 'Недействующий'],
        ],
        TrainingBaseContractStatus::class => [
            TrainingBaseContractStatus::active => ['name' => 'Действующий'],
            TrainingBaseContractStatus::inactive => ['name' => 'Недействующий'],
        ],
        OrganizationStatus::class => [
            OrganizationStatus::active => ['name' => 'Действующая'],
            OrganizationStatus::blocked => ['name' => 'Недействующая'],
        ],
    ];
}
