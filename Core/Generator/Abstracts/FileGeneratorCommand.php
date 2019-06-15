<?php

namespace Core\Generator\Abstracts;

use Core\Core\Api;
use Core\Core\Module;
use Core\Generator\Events\CommandGeneratedEvent;
use Illuminate\Contracts\Filesystem\FileExistsException;
use Illuminate\Support\Str;
use Nwidart\Modules\Commands\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

abstract class FileGeneratorCommand extends AbstractGeneratorCommand
{
    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function setArguments(): array
    {
        return [];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function setOptions() :array
    {
        return [];
    }
}
