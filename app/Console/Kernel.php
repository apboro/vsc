<?php

namespace App\Console;

use App\Jobs\Invoices\CreateInvoicesJob;
use App\Jobs\Invoices\SendInvoicesJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
       $schedule->job(CreateInvoicesJob::class)->dailyAt('00:10');
       $schedule->job(SendInvoicesJob::class)->dailyAt('10:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
    }
}
