<?php

namespace Database\Seeders;

use Database\Seeders\Dictionaries\AccountTransactionTypesSeeder;
use Database\Seeders\Dictionaries\BanksSeeder;
use Database\Seeders\Dictionaries\ClientCommentsSeeder;
use Database\Seeders\Dictionaries\ClientOriginSeeder;
use Database\Seeders\Dictionaries\InvoiceTypesSeeder;
use Database\Seeders\Dictionaries\PatternSeeder;
use Database\Seeders\Dictionaries\RolesSeeder;
use Database\Seeders\Dictionaries\StatusesSeeder;
use Database\Seeders\Dictionaries\ServiceSeeder;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    protected array $seeders = [
        StatusesSeeder::class,
        RolesSeeder::class,
        ServiceSeeder::class,
        PatternSeeder::class,
        ClientCommentsSeeder::class,
        AccountTransactionTypesSeeder::class,
        InvoiceTypesSeeder::class,
        BanksSeeder::class,
        ClientOriginSeeder::class,
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     *
     * @throws BindingResolutionException
     */
    public function run(): void
    {
        foreach ($this->seeders as $seederClass) {

            /** @var GenericSeeder $seeder */
            $seeder = $this->container->make($seederClass);

            $seeder->run();
        }
    }
}
