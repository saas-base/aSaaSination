<?php

namespace Base\Generator\Commands;

use Base\Generator\Abstracts\ClassGeneratorCommand;
use Base\Generator\Events\ExceptionGeneratedEvent;
use Base\Generator\Events\PermissionGeneratedEvent;
use Illuminate\Support\Str;

class PermissionMakeCommand extends ClassGeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'zz:make:permission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new permission interface for the specified module';

    /**
     * The name of the generated resource.
     *
     * @var string
     */
    protected $generatorName = 'permission';

    /**
     * The stub name.
     *
     * @var string
     */
    protected $stub = 'permission.stub';

    /**
     * The file path.
     *
     * @var string
     */
    protected $filePath = '/Permissions';

    /**
     * The event that will fire when the file is created.
     *
     * @var string
     */
    protected $event = PermissionGeneratedEvent::class;

    protected function stubOptions(): array
    {
        return [
            'NAMESPACE'     => $this->getClassNamespace(),
            'CLASS'         => $this->getClassName(),
            'CAPPED_MODULE' => strtoupper($this->getModuleName()),
            'LOWER_MODULE'  => strtolower($this->getModuleName())
        ];
    }
}
