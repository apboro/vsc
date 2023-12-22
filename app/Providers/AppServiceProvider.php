<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(database_path('migrations' . DIRECTORY_SEPARATOR . 'account'));
            $this->loadMigrationsFrom(database_path('migrations' . DIRECTORY_SEPARATOR . 'invoices'));
            $this->loadMigrationsFrom(database_path('migrations' . DIRECTORY_SEPARATOR . 'common'));
            $this->loadMigrationsFrom(database_path('migrations' . DIRECTORY_SEPARATOR . 'dictionaries'));
            $this->loadMigrationsFrom(database_path('migrations' . DIRECTORY_SEPARATOR . 'permissions'));
            $this->loadMigrationsFrom(database_path('migrations' . DIRECTORY_SEPARATOR . 'user'));
            $this->loadMigrationsFrom(database_path('migrations' . DIRECTORY_SEPARATOR . 'positions'));
            $this->loadMigrationsFrom(database_path('migrations' . DIRECTORY_SEPARATOR . 'training_base'));
            $this->loadMigrationsFrom(database_path('migrations' . DIRECTORY_SEPARATOR . 'organizations'));
            $this->loadMigrationsFrom(database_path('migrations' . DIRECTORY_SEPARATOR . 'services'));
            $this->loadMigrationsFrom(database_path('migrations' . DIRECTORY_SEPARATOR . 'clients'));
            $this->loadMigrationsFrom(database_path('migrations' . DIRECTORY_SEPARATOR . 'subscriptions'));
            $this->loadMigrationsFrom(database_path('migrations' . DIRECTORY_SEPARATOR . 'leads'));
        }
    }
}
