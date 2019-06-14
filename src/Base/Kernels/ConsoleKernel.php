<?php

namespace Base\Kernels;

use Base\Console\BootstrapCacheCommand;
use Base\Console\BootstrapClearCacheCommand;
use Base\Console\ClearModelsCacheCommand;
use Base\Console\DatabaseResetCommand;
use Base\Console\DisplayEnvCommand;
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
