<?php

namespace App\Console;

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
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        //TODO 上线时打开此设置，会一分钟发一条短信，直到有人扫描了云班牌
        //$schedule->command('sendMessageForTeacherBeLate 1')->weekdays()->between('7:00', '20:00')->everyMinute();
        //TODO 上线时打开此设置 每小时扫描一次，将管理员上传的新生导入任务执行一条
        //$schedule->command('importerStudent')->hourly();
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
