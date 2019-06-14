<?php

namespace Base\Generator\Abstracts;

use Base\Core\Api;
use Base\Core\Module;
use Base\Generator\Events\CommandGeneratedEvent;
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
