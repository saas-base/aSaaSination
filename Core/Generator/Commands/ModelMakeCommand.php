<?php

namespace Core\Generator\Commands;

use Core\Generator\Abstracts\AbstractGeneratorCommand;
use Core\Generator\Abstracts\ClassGeneratorCommand;
use Core\Generator\Events\ModelGeneratedEvent;
use Core\Generator\Managers\GeneratorManager;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ModelMakeCommand extends ClassGeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model for the specified module.';

    /**
     * The name of the generated resource.
     *
     * @var string
     */
    protected $generatorName = 'model';

    /**
     * The file path.
     *
     * @var string
     */
    protected $filePath = '/Models';

    /**
     * The event that will fire when the file is created.
     *
     * @var string
     */
    protected $event = ModelGeneratedEvent::class;

    protected function stubOptions(): array
    {
        return [
            'NAMESPACE' => $this->getClassNamespace(),
            'CLASS'     => $this->getClassName(),
            'MONGO'     => $this->isMongoModel(),
            'MIGRATION' => $this->needsMigration()
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function setOptions(): array
    {
        return [
            ['mongo', null, InputOption::VALUE_OPTIONAL, 'Mongo model.', false],
            ['migration', null, InputOption::VALUE_OPTIONAL, 'Create migration for the model.', true],
        ];
    }

    protected function handleMongoOption($shortcut, $type, $question, $default)
    {
        return $this->confirm('Is this model for a mongodb database?', $default);
    }

    protected function isMongoModel(): bool
    {
        return $this->getOption('mongo');
    }

    protected function handleMigrationOption($shortcut, $type, $question, $default)
    {
        return $this->confirm('Do you want to create a migration for this model?', $default);
    }

    protected function needsMigration(): bool
    {
        return $this->getOption('migration');
    }

    public function afterGeneration(): void
    {
        if ($this->needsMigration()) {
            if ($this->isMongoModel()) {
                GeneratorManager::module($this->getModuleName(), $this->isOverwriteable())
                    ->createMigration(
                        'Create' . ucfirst($this->getClassName()) . 'Collection',
                        strtolower(split_caps_to_underscore(Str::plural($this->getClassName()))),
                        true
                    );
            } else {
                GeneratorManager::module($this->getModuleName(), $this->isOverwriteable())
                    ->createMigration(
                        'Create' . ucfirst($this->getClassName() . 'Table'),
                        strtolower(split_caps_to_underscore(Str::plural($this->getClassName()))),
                        false
                    );
            }
        }
        GeneratorManager::module($this->getModuleName(), $this->isOverwriteable())
            ->createAttribute($this->getClassName() . 'Attributes');
    }

    /**
     * @return string
     */
    protected function stubName(): string
    {
        if ($this->isMongoModel()) {
            return 'model-mongo.stub';
        }

        return 'model.stub';
    }
}
