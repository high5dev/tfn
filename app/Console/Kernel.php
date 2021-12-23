<?php

namespace App\Console;

use App\Actions\PruneOldPostsAction;
use App\Actions\ScrapeAction;
use App\Actions\ScrapeMemberAction;
use App\Actions\UpdateDailyStatisticsAction;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(new UpdateDailyStatisticsAction)->dailyAt('00:01');
        $schedule->call(new PruneOldPostsAction)->dailyAt('01:01');
        $schedule->call(new ScrapeAction)->cron('0,15,25,30,45 * * * *')->name('Scrape')->withoutOverlapping();
        $schedule->call(new ScrapeMemberAction)->cron('7 * * * *')->name('ScrapeMember')->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
