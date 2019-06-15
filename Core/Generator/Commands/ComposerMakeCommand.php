<?php

namespace Core\Generator\Commands;

use Core\Generator\Abstracts\AbstractGeneratorCommand;
use Core\Generator\Abstracts\FileGeneratorCommand;
use Core\Generator\Events\ComposerGeneratedEvent;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ComposerMakeCommand extends FileGeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'zz:make:composer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new composer file for the specified module';

    /**
     * The name of the generated resource.
     *
     * @var string
     */
    protected $generatorName = 'composer';

    /**
     * The stub name.
     *
     * @var string
     */
    protected $stub = 'composer.stub';

    /**
     * The event that will fire when the file is created.
     *
     * @var string
     */
    protected $event = ComposerGeneratedEvent::class;

    /**
     * The file path.
     *
     * @var string
     */
    protected $filePath = '';

    protected function stubOptions(): array
    {
        return [
            'LOWER_MODULE_NAME' => strtolower($this->getModuleName()),
            'AUTHOR_NAME'       => $this->getAuthorName(),
            'AUTHOR_MAIL'       => $this->getAuthorMail()
        ];
    }

    protected function getAuthorName() :string
    {
        //TODO IMPLEMENT ASKING FOR AUTHOR NAME
        return config('modules.composer.author.name');
    }

    protected function getAuthorMail() :string
    {
        //TODO IMPLEMENT ASKING FOR AUTHOR MAIL
        return config('modules.composer.author.email');
    }

    protected function getFileName() :string
    {
        return 'composer.json';
    }
}
