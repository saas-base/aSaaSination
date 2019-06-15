<?php

namespace Core\Tests;

use Core\Core\Api;
use Illuminate\Support\Str;
use Core\Abstracts\TestCase;
use Core\Traits\DispatchedEvents;
use Illuminate\Support\Facades\Event;
use Core\Traits\DisableRefreshDatabase;
use Core\Generator\Events\DtoGeneratedEvent;
use Core\Generator\Events\JobGeneratedEvent;
use Core\Generator\Events\RuleGeneratedEvent;
use Core\Generator\Events\TestGeneratedEvent;
use Core\Generator\Managers\GeneratorManager;
use Core\Generator\Events\ModelGeneratedEvent;
use Core\Generator\Events\RouteGeneratedEvent;
use Core\Generator\Events\PolicyGeneratedEvent;
use Core\Generator\Events\SeederGeneratedEvent;
use Core\Generator\Events\CommandGeneratedEvent;
use Core\Generator\Events\FactoryGeneratedEvent;
use Core\Generator\Events\RequestGeneratedEvent;
use Core\Generator\Events\ServiceGeneratedEvent;
use Core\Generator\Events\ComposerGeneratedEvent;
use Core\Generator\Events\ListenerGeneratedEvent;
use Core\Generator\Events\ProviderGeneratedEvent;
use Core\Generator\Events\AttributeGeneratedEvent;
use Core\Generator\Events\MigrationGeneratedEvent;
use Core\Generator\Events\ControllerGeneratedEvent;
use Core\Generator\Events\MiddlewareGeneratedEvent;
use Core\Generator\Events\PermissionGeneratedEvent;
use Core\Generator\Events\TransformerGeneratedEvent;
use Core\Generator\Events\NotificationGeneratedEvent;
use Core\Generator\Contracts\ResourceGenerationContract;

/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 10.03.19
 * Time: 18:35
 */
class FileGeneratorTest extends TestCase
{
    use DisableRefreshDatabase, DispatchedEvents;

    public function setUp(): void
    {
        parent::setUp();

        /* Do not remove this line. It prevents the listener that generates the files from executing */
        Event::fake();
    }

    protected function getModuleGenerator(string $moduleName): GeneratorManager
    {
        return GeneratorManager::module($moduleName, true);
    }

    public function testCreateSqlMigration()
    {
        $module = 'User';
        $class  = 'CreateUserTable';
        $table  = 'users';
        $mongo  = false;
        $this->getModuleGenerator($module)->createMigration($class, $table, $mongo);

        $stub  = 'migration.stub';
        $table = 'users';

        /* @var MigrationGeneratedEvent $event */
        $event = $this->getFirstDispatchedEvent(MigrationGeneratedEvent::class);
        $this->assertNotNull($event);
        $this->assertEquals($module, $event->getModuleName());
        $this->assertEquals($stub, $event->getStub()->getName());
        $this->assertEquals($class, $event->getClassName());
        $this->assertEquals($table, $event->getTableName());
        $this->assertEquals($mongo, $event->isMongoMigration());
    }

    public function testCreateMongoMigration()
    {
        $module = 'User';
        $class  = 'CreateUserTable';
        $table  = 'users';
        $mongo  = true;
        $this->getModuleGenerator($module)->createMigration($class, $table, $mongo);

        $stub  = 'migration-mongo.stub';
        $table = 'users';

        /* @var MigrationGeneratedEvent $event */
        $event = $this->getFirstDispatchedEvent(MigrationGeneratedEvent::class);
        $this->assertNotNull($event);
        $this->assertEquals($module, $event->getModuleName());
        $this->assertEquals($stub, $event->getStub()->getName());
        $this->assertEquals($class, $event->getClassName());
        $this->assertEquals($table, $event->getTableName());
        $this->assertEquals($mongo, $event->isMongoMigration());
    }

    public function testCreateFactory()
    {
        $module = 'User';
        $model  = $module;
        $class  = $model . 'Factory';
        $this->getModuleGenerator($module)->createFactory($module);

        $path            = Api::getModule($module)->getFactories()->getPath() . "/$class.php";
        $stub            = 'factory.stub';
        $modelNamespace  = "Modules\User\Models\User";

        /* @var FactoryGeneratedEvent $event */
        $event = $this->getFirstDispatchedEvent(FactoryGeneratedEvent::class);
        $this->assertFileBasics(
            $event,
            $module,
            $stub,
            $path
        );
        $this->assertEquals($class, $event->getClassName());
        $this->assertEquals($model, $event->getModel());
        $this->assertEquals($modelNamespace, $event->getModelNamespace());
    }

    public function testCreateController()
    {
        $module = 'User';
        $class  = 'UserController';
        $this->getModuleGenerator($module)->createController($class);

        $path      = Api::getModule($module)->getControllers()->getPath() . "/$class.php";
        $stub      = 'controller.stub';
        $namespace = "Modules\User\Controllers";

        /* @var ControllerGeneratedEvent $event */
        $event = $this->getFirstDispatchedEvent(ControllerGeneratedEvent::class);
        $this->assertClassBasics(
            $event,
            $module,
            $stub,
            $class,
            $namespace,
            $path
        );
    }

    public function testCreateAttribute()
    {
        $module = 'User';
        $class  = 'UserAttribute';
        $this->getModuleGenerator($module)->createAttribute($class);

        $path      = Api::getModule($module)->getAttributes()->getPath() . "/$class.php";
        $stub      = 'attribute.stub';
        $namespace = "Modules\User\Attributes";

        /* @var AttributeGeneratedEvent $event */
        $event = $this->getFirstDispatchedEvent(AttributeGeneratedEvent::class);
        $this->assertClassBasics(
            $event,
            $module,
            $stub,
            $class,
            $namespace,
            $path
        );
    }

    public function testCreateDto()
    {
        $module = 'User';
        $class  = 'CreateUserData';
        $this->getModuleGenerator($module)->createDto($class);

        $path      = Api::getModule($module)->getDtos()->getPath() . "/$class.php";
        $stub      = 'dto.stub';
        $namespace = "Modules\User\Dtos";

        /* @var DtoGeneratedEvent $event */
        $event = $this->getFirstDispatchedEvent(DtoGeneratedEvent::class);
        $this->assertClassBasics(
            $event,
            $module,
            $stub,
            $class,
            $namespace,
            $path
        );
    }

    public function testCreateListener()
    {
        $module     = 'User';
        $class      = 'SendWelcomeMail';
        $eventClass = 'UserRegisteredEvent';
        $queued     = false;
        $this->getModuleGenerator($module)->createListener($class, $eventClass, $queued);

        $path           = Api::getModule($module)->getListeners()->getPath() . '/SendWelcomeMail.php';
        $stub           = 'listener.stub';
        $namespace      = "Modules\User\Listeners";
        $eventNamespace = "Modules\User\Events\UserRegisteredEvent";

        /* @var ListenerGeneratedEvent $event */
        $event = $this->getFirstDispatchedEvent(ListenerGeneratedEvent::class);
        $this->assertClassBasics(
            $event,
            $module,
            $stub,
            $class,
            $namespace,
            $path
        );
        $this->assertEquals($queued, $event->isQueued());
        $this->assertEquals($eventClass, $event->getEvent());
        $this->assertEquals($eventNamespace, $event->getEventNamespace());
    }

    public function testCreateQueuedListener()
    {
        $module     = 'User';
        $class      = 'SendWelcomeMail';
        $eventClass = 'UserRegisteredEvent';
        $queued     = true;
        $this->getModuleGenerator($module)->createListener($class, $eventClass, $queued);

        $path           = Api::getModule($module)->getListeners()->getPath() . '/SendWelcomeMail.php';
        $stub           = 'listener-queued.stub';
        $namespace      = "Modules\User\Listeners";
        $eventNamespace = "Modules\User\Events\UserRegisteredEvent";

        /* @var ListenerGeneratedEvent $event */
        $event = $this->getFirstDispatchedEvent(ListenerGeneratedEvent::class);
        $this->assertClassBasics(
            $event,
            $module,
            $stub,
            $class,
            $namespace,
            $path
        );
        $this->assertEquals($queued, $event->isQueued());
        $this->assertEquals($eventClass, $event->getEvent());
        $this->assertEquals($eventNamespace, $event->getEventNamespace());
    }

    public function testCreateJob()
    {
        $module      = 'User';
        $class       = 'RandomUserJob';
        $synchronous = false;

        $this->getModuleGenerator($module)->createJob($class, $synchronous);

        $path      = Api::getModule($module)->getJobs()->getPath() . "/$class.php";
        $stub      = 'job-queued.stub';
        $namespace = "Modules\User\Jobs";

        /* @var JobGeneratedEvent $event */
        $event = $this->getFirstDispatchedEvent(JobGeneratedEvent::class);
        $this->assertClassBasics(
            $event,
            $module,
            $stub,
            $class,
            $namespace,
            $path
        );
        $this->assertEquals($synchronous, $event->isSynchronous());
    }

    public function testCreateSynchronousJob()
    {
        $module      = 'User';
        $class       = 'RandomUserJob';
        $synchronous = true;

        $this->getModuleGenerator($module)->createJob($class, $synchronous);

        $path      = Api::getModule($module)->getJobs()->getPath() . "/$class.php";
        $stub      = 'job.stub';
        $namespace = "Modules\User\Jobs";

        /* @var JobGeneratedEvent $event */
        $event = $this->getFirstDispatchedEvent(JobGeneratedEvent::class);
        $this->assertClassBasics(
            $event,
            $module,
            $stub,
            $class,
            $namespace,
            $path
        );
        $this->assertEquals($synchronous, $event->isSynchronous());
    }

    public function testCreateCommand()
    {
        $module         = 'User';
        $class          = 'RandomCommand';
        $consoleCommand = 'user:dosomethingrandom';

        $this->getModuleGenerator($module)->createCommand($class, $consoleCommand);

        $path      = Api::getModule($module)->getCommands()->getPath() . "/$class.php";
        $stub      = 'command.stub';
        $namespace = "Modules\User\Console";


        /* @var CommandGeneratedEvent $event */
        $event = $this->getFirstDispatchedEvent(CommandGeneratedEvent::class);
        $this->assertClassBasics(
            $event,
            $module,
            $stub,
            $class,
            $namespace,
            $path
        );
        $this->assertEquals($consoleCommand, $event->getConsoleCommand());
    }

    public function testCreateMiddleware()
    {
        $module = 'User';
        $class  = 'RandomMiddleware';

        $this->getModuleGenerator($module)->createMiddleware($class);

        $path      = Api::getModule($module)->getMiddleWare()->getPath() . "/$class.php";
        $stub      = 'middleware.stub';
        $namespace = "Modules\User\Middleware";

        /* @var MiddlewareGeneratedEvent $event */
        $event = $this->getFirstDispatchedEvent(MiddlewareGeneratedEvent::class);
        $this->assertClassBasics(
            $event,
            $module,
            $stub,
            $class,
            $namespace,
            $path
        );
    }

    public function testCreateProvider()
    {
        $module = 'User';
        $class  = 'RandomServiceProvider';

        $this->getModuleGenerator($module)->createServiceProvider($class);

        $path      = Api::getModule($module)->getServiceProviders()->getPath() . "/$class.php";
        $stub      = 'provider.stub';
        $namespace = "Modules\User\Providers";

        /* @var ProviderGeneratedEvent $event */
        $event = $this->getFirstDispatchedEvent(ProviderGeneratedEvent::class);
        $this->assertClassBasics(
            $event,
            $module,
            $stub,
            $class,
            $namespace,
            $path
        );
    }

    public function testCreateService()
    {
        $module = 'User';
        $class  = 'UserService';

        $this->getModuleGenerator($module)->createService($class);

        $path      = Api::getModule($module)->getServices()->getPath() . "/$class.php";
        $stub      = 'service.stub';
        $namespace = "Modules\User\Services";

        /* @var ServiceGeneratedEvent $event */
        $event = $this->getFirstDispatchedEvent(ServiceGeneratedEvent::class);
        $this->assertClassBasics(
            $event,
            $module,
            $stub,
            $class,
            $namespace,
            $path
        );
    }

    public function testCreateNotification()
    {
        $module = 'User';
        $class  = 'RandomNotification';

        $this->getModuleGenerator($module)->createNotification($class);

        $path      = Api::getModule($module)->getNotifications()->getPath() . "/$class.php";
        $stub      = 'notification.stub';
        $namespace = "Modules\User\Notifications";

        /* @var NotificationGeneratedEvent $event */
        $event = $this->getFirstDispatchedEvent(NotificationGeneratedEvent::class);
        $this->assertClassBasics(
            $event,
            $module,
            $stub,
            $class,
            $namespace,
            $path
        );
    }

    public function testCreateModel()
    {
        $module         = 'User';
        $modelName      = 'Address';
        $class          = $module . $modelName;
        $needsMigration = true;
        $isMongoModel   = false;

        $this->getModuleGenerator($module)->createModel($class, $isMongoModel, $needsMigration);

        $path      = Api::getModule($module)->getModels()->getPath() . "/$module$modelName.php";
        $stub      = 'model.stub';
        $namespace = "Modules\User\Models";

        /* @var ModelGeneratedEvent $event */
        $event = $this->getFirstDispatchedEvent(ModelGeneratedEvent::class);
        $this->assertClassBasics(
            $event,
            $module,
            $stub,
            $class,
            $namespace,
            $path
        );

        $stub           = 'migration.stub';
        $table          = 'user_addresses';
        $migrationClass = 'CreateUserAddressTable';

        /* @var MigrationGeneratedEvent $event */
        $event = $this->getFirstDispatchedEvent(MigrationGeneratedEvent::class);
        $this->assertNotNull($event);
        $this->assertEquals($module, $event->getModuleName());
        $this->assertEquals($stub, $event->getStub()->getName());
        $this->assertEquals($migrationClass, $event->getClassName());
        $this->assertEquals($table, $event->getTableName());
    }

    public function testCreatePolicy()
    {
        $module = 'User';
        $class  = 'UserOwnershipPolicy';

        $this->getModuleGenerator($module)->createPolicy($class);

        $path      = Api::getModule($module)->getPolicies()->getPath() . "/$class.php";
        $stub      = 'policy.stub';
        $namespace = "Modules\User\Policies";

        /* @var PolicyGeneratedEvent $event */
        $event = $this->getFirstDispatchedEvent(PolicyGeneratedEvent::class);
        $this->assertClassBasics(
            $event,
            $module,
            $stub,
            $class,
            $namespace,
            $path
        );
    }

    public function testCreateTransformer()
    {
        $module = 'User';
        $model  = 'User';
        $class  = 'BlablaTransformer';

        $this->getModuleGenerator($module)->createTransformer($class, $model);

        $path           = Api::getModule($module)->getTransformers()->getPath() . "/$class.php";
        $stub           = 'transformer.stub';
        $namespace      = "Modules\User\Transformers";
        $modelNamespace = "Modules\User\Models\User";

        /* @var TransformerGeneratedEvent $event */
        $event = $this->getFirstDispatchedEvent(TransformerGeneratedEvent::class);
        $this->assertClassBasics(
            $event,
            $module,
            $stub,
            $class,
            $namespace,
            $path
        );
        $this->assertEquals($model, $event->getModel());
        $this->assertEquals($modelNamespace, $event->getModelNamespace());
    }

    public function testCreateUnitTest()
    {
        $module = 'User';
        $class  = 'AUserUnitTest';

        $this->getModuleGenerator($module)->createTest($class, 'unit');

        $path      = Api::getModule($module)->getTests()->getPath() . "/$class.php";
        $stub      = 'test-unit.stub';
        $namespace = "Modules\User\Tests";
        $type      = 'unit';

        /* @var TestGeneratedEvent $event */
        $event = $this->getFirstDispatchedEvent(TestGeneratedEvent::class);
        $this->assertClassBasics(
            $event,
            $module,
            $stub,
            $class,
            $namespace,
            $path
        );
        $this->assertEquals($type, $event->getType());
    }

    public function testCreateRequest()
    {
        $module = 'User';
        $class  = 'ARequest';

        $this->getModuleGenerator($module)->createRequest($class);

        $path      = Api::getModule($module)->getRequests()->getPath() . "/$class.php";
        $stub      = 'request.stub';
        $namespace = "Modules\User\Requests";

        /* @var RequestGeneratedEvent $event */
        $event = $this->getFirstDispatchedEvent(RequestGeneratedEvent::class);
        $this->assertClassBasics(
            $event,
            $module,
            $stub,
            $class,
            $namespace,
            $path
        );
    }

    public function testCreateRule()
    {
        $module   = 'User';
        $fileName = 'BlalkaRule';

        $this->getModuleGenerator($module)->createRule($fileName);

        $path      = Api::getModule($module)->getRules()->getPath() . "/$fileName.php";
        $stub      = 'rule.stub';
        $class     = 'BlalkaRule';
        $namespace = "Modules\User\Rules";

        /* @var RuleGeneratedEvent $event */
        $event = $this->getFirstDispatchedEvent(RuleGeneratedEvent::class);
        $this->assertClassBasics(
            $event,
            $module,
            $stub,
            $class,
            $namespace,
            $path
        );
    }

    public function testCreateSeeder()
    {
        $module = 'User';
        $class  = 'BlablaSeeder';

        $this->getModuleGenerator($module)->createSeeder($class);

        $path      = Api::getModule($module)->getSeeders()->getPath() . "/$class.php";
        $stub      = 'seeder.stub';
        $namespace = "Modules\User\Database\Seeders";

        /* @var SeederGeneratedEvent $event */
        $event = $this->getFirstDispatchedEvent(SeederGeneratedEvent::class);
        $this->assertClassBasics(
            $event,
            $module,
            $stub,
            $class,
            $namespace,
            $path
        );
    }

    public function testCreatePermission()
    {
        $module = 'User';
        $class  = 'UserPermission';

        $this->getModuleGenerator($module)->createPermission($class);

        $path      = Api::getModule($module)->getPermissions()->getPath() . "/$class.php";
        $stub      = 'permission.stub';
        $namespace = "Modules\User\Permissions";

        /* @var PermissionGeneratedEvent $event */
        $event = $this->getFirstDispatchedEvent(PermissionGeneratedEvent::class);
        $this->assertClassBasics(
            $event,
            $module,
            $stub,
            $class,
            $namespace,
            $path
        );
    }

    public function testCreateRoute()
    {
        $moduleName = 'Demo';
        $version    = 'v1';
        $routename  = strtolower(Str::plural($moduleName)) . ".$version";

        $this->getModuleGenerator($moduleName)->createRoute($version);

        $expectedFileName = Api::getModule($moduleName)->getRoutes()->getPath() . "/$routename.php";
        $expectedStubName = 'route.stub';
        $expectedVersion  = 'v1';

        /* @var RouteGeneratedEvent $event */
        $event = $this->getFirstDispatchedEvent(RouteGeneratedEvent::class);
        $this->assertNotNull($event);

        $this->assertEquals($expectedFileName, $event->getFilePath());
        $this->assertEquals($expectedStubName, $event->getStub()->getName());
        $this->assertEquals($expectedVersion, $event->getVersion());
    }

    public function testCreateComposer()
    {
        $moduleName = 'Demo';

        $this->getModuleGenerator($moduleName)->createComposer();

        $expectedFileName = Api::getModule($moduleName)->getPath() . '/composer.json';
        $expectedStubName = 'composer.stub';
        $authorName       = 'arthur devious';
        $authorMail       = 'aamining2@gmail.com';

        /* @var ComposerGeneratedEvent $event */
        $event = $this->getFirstDispatchedEvent(ComposerGeneratedEvent::class);
        $this->assertNotNull($event);

        $this->assertEquals($expectedFileName, $event->getFilePath());
        $this->assertEquals($expectedStubName, $event->getStub()->getName());
        $this->assertEquals($authorName, $event->getAuthorName());
        $this->assertEquals($authorMail, $event->getAuthorMail());
    }

    /**
     * @param ResourceGenerationContract $event
     * @param string $module
     * @param $stub
     * @param string $class
     * @param string $namespace
     * @param string $path
     */
    private function assertClassBasics(?ResourceGenerationContract $event, string $module, $stub, string $class, string $namespace, string $path)
    {
        $this->assertFileBasics($event, $module, $stub, $path);
        $this->assertEquals($namespace, $event->getNamespace());
        $this->assertEquals($class, $event->getClassName());
        $this->assertEquals($path, $event->getFilePath());
    }

    private function assertFileBasics(?ResourceGenerationContract $event, string $module, $stub, string $path)
    {
        $this->assertNotNull($event);
        $this->assertEquals($module, $event->getModuleName());
        $this->assertEquals($stub, $event->getStub()->getName());
        $this->assertEquals($path, $event->getFilePath());
    }
}
