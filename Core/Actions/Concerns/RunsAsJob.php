<?php

namespace Core\Actions\Concerns;

trait RunsAsJob
{
    public function runAsJob()
    {
        $this->runningAs = 'job';

        return $this->run();
    }
}
