<?php

namespace Core\Generator\Providers;

use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Providers\ConsoleServiceProvider as BaseConsoleServiceProvider;

final class ConsoleServiceProvider extends BaseConsoleServiceProvider
{
    /**
     * The available commands
     *
     * @var array
     */
    protected $commands = [
    ];

    /**
     * Register the commands.
     */
    public function register()
    {
        $this->commands($this->commands);
    }

    /**
     * @return array
     */
    public function provides()
    {
        $provides = $this->commands;

        return $provides;
    }
}
