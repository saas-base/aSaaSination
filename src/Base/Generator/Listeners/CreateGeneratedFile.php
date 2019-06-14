<?php

namespace Base\Generator\Listeners;

use Base\Contracts\Abstracts\Listener;
use Base\Generator\Contracts\ResourceGenerationContract;
use Base\Generator\Generators\FileGenerator;
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
