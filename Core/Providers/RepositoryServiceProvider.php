<?php

namespace Repository\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../Resources/config/repository.php' => config_path('repository.php'),
        ]);

        $this->mergeConfigFrom(config_path('repository.php'), 'repository');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'repository');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands('Repository\Generators\Commands\RepositoryCommand');
        $this->commands('Repository\Generators\Commands\TransformerCommand');
        $this->commands('Repository\Generators\Commands\PresenterCommand');
        $this->commands('Repository\Generators\Commands\EntityCommand');
        $this->commands('Repository\Generators\Commands\ValidatorCommand');
        $this->commands('Repository\Generators\Commands\ControllerCommand');
        $this->commands('Repository\Generators\Commands\BindingsCommand');
        $this->commands('Repository\Generators\Commands\CriteriaCommand');
        $this->app->register('Repository\Providers\EventServiceProvider');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
