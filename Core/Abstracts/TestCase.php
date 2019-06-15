<?php

namespace Core\Abstracts;

use Modules\User\Models\User;
use Core\Traits\RefreshDatabase;
use Modules\User\Services\UserService;
use Modules\User\Contracts\UserServiceContract;
use Modules\Authorization\Contracts\AuthorizationContract;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase, CreatesApplication;

    /**
     * @var UserService
     */
    private $userService;

    public function setUp(): void
    {
        parent::setUp();
        $this->userService = $this->app->make(UserServiceContract::class);
        $this->app->make(AuthorizationContract::class)->clearPermissionCache();
        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->registerPermissions();
        $this->actingAs($this->actingUser());
        $this->seedData();
    }

    protected function seedData()
    {
    }

    private function createUser()
    {
        return $this->userService->create(factory(User::class)->raw());
    }

    protected function actingUser()
    {
        return $this->getRandomUser();
    }

    public function actingAs($user, $driver = null)
    {
        parent::actingAs($user, $driver);
    }

    private function getRandomUser(): User
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $user = $this->createUser();
        } else {
            $user = $users->random();
        }

        return $user;
    }

    protected function actAsRandomUser(): User
    {
        $user = $this->getRandomUser();
        $this->actingAs($user);

        return $user;
    }

    protected function getActingUser(): User
    {
        return auth()->user();
    }
}
