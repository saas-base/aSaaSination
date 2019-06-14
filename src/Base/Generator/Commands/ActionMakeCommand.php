<?php

namespace Base\Generator\Commands;

use Base\Generator\Abstracts\ClassGeneratorCommand;
use Base\Generator\Events\ActionGeneratedEvent;

class ActionMakeCommand extends ClassGeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'zz:make:action';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new single responsibility action class for the specified module (see: lorisleiva/laravel-actions)';

    /**
     * The name of the generated resource.
     *
     * @var string
     */
    protected $generatorName = 'action';

    /**
     * The stub name.
     *
     * @var string
     */
    protected $stub = 'action.stub';

    /**
     * The file path.
     *
     * @var string
     */
    protected $filePath = '/Actions';

    /**
     * The event that will fire when the file is created.
     *
     * @var string
     */
    protected $event = ActionGeneratedEvent::class;

    protected function stubOptions(): array
    {
        return [
            'NAMESPACE' => $this->getClassNamespace(),
            'CLASS'     => $this->getClassName(),
        ];
    }
}
