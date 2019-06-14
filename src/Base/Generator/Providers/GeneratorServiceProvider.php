<?php

namespace Base\Generator\Providers;

use Base\Generator\Commands\ActionMakeCommand;
use Base\Generator\Commands\AttributeMakeCommand;
use Base\Generator\Commands\CommandMakeCommand;
use Base\Generator\Commands\ComposerMakeCommand;
use Base\Generator\Commands\ControllerMakeCommand;
use Base\Generator\Commands\DtoMakeCommand;
use Base\Generator\Commands\EventMakeCommand;
use Base\Generator\Commands\ExceptionMakeCommand;
use Base\Generator\Commands\FactoryMakeCommand;
use Base\Generator\Commands\GuardMakeCommand;
use Base\Generator\Commands\JobMakeCommand;
use Base\Generator\Commands\ListenerMakeCommand;
use Base\Generator\Commands\MiddlewareMakeCommand;
use Base\Generator\Commands\MigrationMakeCommand;
use Base\Generator\Commands\ModelMakeCommand;
use Base\Generator\Commands\ModuleMakeCommand;
use Base\Generator\Commands\NotificationMakeCommand;
use Base\Generator\Commands\PermissionMakeCommand;
use Base\Generator\Commands\PolicyMakeCommand;
use Base\Generator\Commands\ProviderMakeCommand;
use Base\Generator\Commands\RequestMakeCommand;
use Base\Generator\Commands\RouteMakeCommand;
use Base\Generator\Commands\RuleMakeCommand;
use Base\Generator\Commands\SeederMakeCommand;
use Base\Generator\Commands\ServiceContractMakeCommand;
use Base\Generator\Commands\ServiceMakeCommand;
use Base\Generator\Commands\TestMakeCommand;
use Base\Generator\Commands\TransformerMakeCommand;
use Base\Generator\Contracts\ResourceGenerationContract;
use Base\Generator\Listeners\CreateGeneratedFile;
use Illuminate\Support\ServiceProvider;

class GeneratorServiceProvider extends ServiceProvider
{
    protected $commands = [
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
