<?php

namespace Core\Generator\Commands;

use Core\Generator\Abstracts\ClassGeneratorCommand;
use Core\Generator\Events\EventGeneratedEvent;
use Core\Generator\Events\ServiceGeneratedEvent;
use Core\Generator\Managers\GeneratorManager;

class ServiceMakeCommand extends ClassGeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'zz:make:service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class for the specified module';

    /**
     * The name of the generated resource.
     *
     * @var string
     */
    protected $generatorName = 'service';

    /**
     * The stub name.
     *
     * @var string
     */
    protected $stub = 'service.stub';

    /**
     * The file path.
     *
     * @var string
     */
    protected $filePath = '/Services';

    /**
     * The event that will fire when the file is created.
     *
     * @var string
     */
    protected $event = ServiceGeneratedEvent::class;

    protected function stubOptions(): array
    {
        return [
            'NAMESPACE' => $this->getClassNamespace(),
            'CLASS'     => $this->getClassName(),
        ];
    }

    public function afterGeneration(): void
    {
        GeneratorManager::module($this->getModuleName(), $this->isOverwriteable())
            ->createServiceContract(ucfirst($this->getClassName()).'Contract');

        GeneratorManager::module($this->getModuleName(), $this->isOverwriteable())
            ->createDto($this->getCleanName().'CreateDto');

        GeneratorManager::module($this->getModuleName(), $this->isOverwriteable())
            ->createDto($this->getCleanName().'UpdateDto');
    }
}
