<?php

namespace App\Console;

use App\Events\TestMeEvent;
use App\Jobs\TestMeJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->job(new TestMeJob())
//            ->daily()
            ->everyMinute()
            ->description('Test Me job')
            ->onSuccess(function () {
                return event(new TestMeEvent("OK Event load .. .".date('H:i:s')));
            })->onFailure(function () {
                return event(new TestMeEvent("Failed Event load ...".date('H:i:s')));
            });
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
