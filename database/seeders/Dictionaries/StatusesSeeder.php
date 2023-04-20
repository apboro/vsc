<?php

namespace Database\Seeders\Dictionaries;

use App\Models\Dictionaries\ClientStatus;
use App\Models\Dictionaries\ClientWardStatus;
use App\Models\Dictionaries\LeadStatus;
use App\Models\Dictionaries\OrganizationStatus;
use App\Models\Dictionaries\PositionStatus;
use App\Models\Dictionaries\ServiceStatus;
use App\Models\Dictionaries\SubscriptionContractStatus;
use App\Models\Dictionaries\SubscriptionStatus;
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
            TrainingBaseContractStatus::active => ['name' => 'Договор подписан'],
            TrainingBaseContractStatus::inactive => ['name' => 'Договор в проекте'],
        ],
        OrganizationStatus::class => [
            OrganizationStatus::active => ['name' => 'Действующая'],
            OrganizationStatus::blocked => ['name' => 'Недействующая'],
        ],
        ServiceStatus::class => [
            ServiceStatus::enabled => ['name' => 'Действующая'],
            ServiceStatus::disabled => ['name' => 'Недействующая'],
        ],
        LeadStatus::class => [
            LeadStatus::new => ['name' => 'Новый'],
            LeadStatus::client_created => ['name' => 'Создан клиент'],
            LeadStatus::deleted => ['name' => 'Удален'],
        ],
        ClientStatus::class => [
            ClientStatus::active => ['name' => 'Активный'],
        ],
        ClientWardStatus::class => [
            ClientWardStatus::active => ['name' => 'Активный'],
        ],
        SubscriptionStatus::class => [
            SubscriptionStatus::new => ['name' => 'Заполнение договора'],
            SubscriptionStatus::fill => ['name' => 'Повторное заполнение договора'],
            SubscriptionStatus::filled => ['name' => 'Договор заполнен'],
            SubscriptionStatus::sent => ['name' => 'Договор отправлен'],
            SubscriptionStatus::closed => ['name' => 'Подписка закрыта'],
        ],
        SubscriptionContractStatus::class => [
            SubscriptionContractStatus::draft => ['name' => 'Заполнен'],
            SubscriptionContractStatus::accepted => ['name' => 'Договор сформирован'],
            SubscriptionContractStatus::closed => ['name' => 'Договор закрыт'],
        ],
    ];
}
