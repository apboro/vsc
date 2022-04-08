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
        //
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
            $this->loadMigrationsFrom(database_path('migrations' . DIRECTORY_SEPARATOR . 'common'));
            $this->loadMigrationsFrom(database_path('migrations' . DIRECTORY_SEPARATOR . 'dictionaries'));
            $this->loadMigrationsFrom(database_path('migrations' . DIRECTORY_SEPARATOR . 'permissions'));
            $this->loadMigrationsFrom(database_path('migrations' . DIRECTORY_SEPARATOR . 'user'));
            $this->loadMigrationsFrom(database_path('migrations' . DIRECTORY_SEPARATOR . 'positions'));
        }
    }
}
