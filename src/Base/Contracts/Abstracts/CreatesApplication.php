<?php

namespace Base\Contracts\Abstracts;

use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $bootstrapFile = base_path('/bootstrap/app.php');
        $app           = require $bootstrapFile;

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
