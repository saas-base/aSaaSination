<?php

namespace Core\Generator\Commands;

use Core\Generator\Abstracts\ClassGeneratorCommand;
use Core\Generator\Events\SeederGeneratedEvent;

class SeederMakeCommand extends ClassGeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'zz:make:seeder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new seeder for the specified module.';

    /**
     * The name of the generated resource.
     *
     * @var string
     */
    protected $generatorName = 'seeder';

    /**
     * The stub name.
     *
     * @var string
     */
    protected $stub = 'seeder.stub';

    /**
     * The file path.
     *
     * @var string
     */
    protected $filePath = '/Database/Seeders';

    /**
     * The event that will fire when the file is created.
     *
     * @var string
     */
    protected $event = SeederGeneratedEvent::class;

    protected function stubOptions(): array
    {
        return [
            'NAMESPACE' => $this->getClassNamespace(),
            'CLASS'     => $this->getClassName(),
        ];
    }
}
