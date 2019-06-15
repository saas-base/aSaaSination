<?php

namespace Core\Generator\Listeners;

use Core\Abstracts\Listener;
use Core\Generator\Generators\FileGenerator;
use Core\Generator\Contracts\ResourceGenerationContract;
use Nwidart\Modules\Exceptions\FileAlreadyExistException;

/**
 * Class CreateGeneratedFile
 * @package Base\Generator\Listeners
 */
class CreateGeneratedFile extends Listener
{
    /**
     * Handle the event.
     *
     * @param  ResourceGenerationContract $event
     * @throws FileAlreadyExistException
     * @return void
     */
    public function handle(ResourceGenerationContract $event)
    {
        if (file_exists($event->getFilePath())) {
            unlink($event->getFilePath());
        }

        (new FileGenerator(
            $event->getFilePath(),
            $event->getStub()->render()
        ))->generate();
    }
}
