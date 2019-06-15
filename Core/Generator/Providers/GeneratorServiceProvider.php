<?php

namespace Core\Generator\Providers;

use Core\Generator\Commands\ActionMakeCommand;
use Core\Generator\Commands\AttributeMakeCommand;
use Core\Generator\Commands\CommandMakeCommand;
use Core\Generator\Commands\ComposerMakeCommand;
use Core\Generator\Commands\ControllerMakeCommand;
use Core\Generator\Commands\DtoMakeCommand;
use Core\Generator\Commands\EventMakeCommand;
use Core\Generator\Commands\ExceptionMakeCommand;
use Core\Generator\Commands\FactoryMakeCommand;
use Core\Generator\Commands\GuardMakeCommand;
use Core\Generator\Commands\JobMakeCommand;
use Core\Generator\Commands\ListenerMakeCommand;
use Core\Generator\Commands\MiddlewareMakeCommand;
use Core\Generator\Commands\MigrationMakeCommand;
use Core\Generator\Commands\ModelMakeCommand;
use Core\Generator\Commands\ModuleMakeCommand;
use Core\Generator\Commands\NotificationMakeCommand;
use Core\Generator\Commands\PermissionMakeCommand;
use Core\Generator\Commands\PolicyMakeCommand;
use Core\Generator\Commands\ProviderMakeCommand;
use Core\Generator\Commands\RequestMakeCommand;
use Core\Generator\Commands\RouteMakeCommand;
use Core\Generator\Commands\RuleMakeCommand;
use Core\Generator\Commands\SeederMakeCommand;
use Core\Generator\Commands\ServiceContractMakeCommand;
use Core\Generator\Commands\ServiceMakeCommand;
use Core\Generator\Commands\TestMakeCommand;
use Core\Generator\Commands\TransformerMakeCommand;
use Core\Generator\Contracts\ResourceGenerationContract;
use Core\Generator\Listeners\CreateGeneratedFile;
use Illuminate\Support\ServiceProvider;

class GeneratorServiceProvider extends ServiceProvider
{
    protected $commands = [
        ActionMakeCommand::class,
        FactoryMakeCommand::class,
        CommandMakeCommand::class,
        ControllerMakeCommand::class,
        EventMakeCommand::class,
        JobMakeCommand::class,
        ListenerMakeCommand::class,
        MiddlewareMakeCommand::class,
        MigrationMakeCommand::class,
        ProviderMakeCommand::class,
        NotificationMakeCommand::class,
        ModelMakeCommand::class,
        PolicyMakeCommand::class,
        TestMakeCommand::class,
        RuleMakeCommand::class,
        TransformerMakeCommand::class,
        RequestMakeCommand::class,
        SeederMakeCommand::class,
        RuleMakeCommand::class,
        ComposerMakeCommand::class,
        RouteMakeCommand::class,
        ServiceMakeCommand::class,
        ServiceContractMakeCommand::class,
        ModuleMakeCommand::class,
        ExceptionMakeCommand::class,
        PermissionMakeCommand::class,
        AttributeMakeCommand::class,
        DtoMakeCommand::class,
        GuardMakeCommand::class
    ];

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        \Event::listen(ResourceGenerationContract::class, CreateGeneratedFile::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands($this->commands);
    }
}
