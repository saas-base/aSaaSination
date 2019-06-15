<?php

namespace Core\Kernels;

use Core\Console\BootstrapCacheCommand;
use Core\Console\BootstrapClearCacheCommand;
use Core\Console\ClearModelsCacheCommand;
use Core\Console\DatabaseResetCommand;
use Core\Console\DisplayEnvCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as LaravelConsoleKernel;

class ConsoleKernel extends LaravelConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        BootstrapCacheCommand::class,
        BootstrapClearCacheCommand::class,
        DatabaseResetCommand::class,
        ClearModelsCacheCommand::class,
        DisplayEnvCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('horizon:snapshot')->everyFiveMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('src/Base/Routes/console.php');
    }
}
