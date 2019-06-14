<?php

namespace Base\Generator\Commands;

use Base\Generator\Abstracts\AbstractGeneratorCommand;
use Base\Generator\Abstracts\ClassGeneratorCommand;
use Base\Generator\Events\PolicyGeneratedEvent;

class PolicyMakeCommand extends ClassGeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'zz:make:policy';

    /**
     * The name of the generated resource.
     *
     * @var string
     */
    protected $generatorName = 'policy';

    /**
     * The stub name.
     *
     * @var string
     */
    protected $stub = 'policy.stub';

    /**
     * The file path.
     *
     * @var string
     */
    protected $filePath = '/Policies';

    /**
     * The event that will fire when the file is created.
     *
     * @var string
     */
    protected $event = PolicyGeneratedEvent::class;

    protected function stubOptions(): array
    {
        return [
            'NAMESPACE' => $this->getClassNamespace(),
            'CLASS'     => $this->getClassName(),
        ];
    }
}
