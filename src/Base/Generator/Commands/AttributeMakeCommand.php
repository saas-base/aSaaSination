<?php

namespace Base\Generator\Commands;

use Base\Generator\Abstracts\ClassGeneratorCommand;
use Base\Generator\Events\AttributeGeneratedEvent;
use Base\Generator\Events\EventGeneratedEvent;
use Base\Generator\Events\ServiceGeneratedEvent;
use Base\Generator\Managers\GeneratorManager;

class AttributeMakeCommand extends ClassGeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'zz:make:attribute';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new attribute interface for a model';

    /**
     * The name of the generated resource.
     *
     * @var string
     */
    protected $generatorName = 'attribute';

    /**
     * The stub name.
     *
     * @var string
     */
    protected $stub = 'attribute.stub';

    /**
     * The file path.
     *
     * @var string
     */
    protected $filePath = '/Attributes';

    /**
     * The event that will fire when the file is created.
     *
     * @var string
     */
    protected $event = AttributeGeneratedEvent::class;

    protected function stubOptions(): array
    {
        return [
            'NAMESPACE' => $this->getClassNamespace(),
            'CLASS'     => $this->getClassName(),
        ];
    }

    protected function afterGeneration(): void
    {
        $this->info("don't forget to implement this attribute on the model");
    }
}
