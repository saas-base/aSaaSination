<?php

namespace Core\Tests;

use Core\Core\Api;
use Core\Core\Module;
use Core\Abstracts\TestCase;
use Core\Traits\DisableRefreshDatabase;

class CoreTest extends TestCase
{
    use DisableRefreshDatabase;

    public function testGetModules()
    {
        $modules = Api::getModules();

        foreach ($modules as $module) {
            $this->assertInstanceOf(Module::class, $module);
        }
    }

    public function testGetModuleNames()
    {
        $moduleNames = Api::getModuleNames();
        $this->assertContains('User', $moduleNames);
    }

    public function testUserNamespace()
    {
        $module = Api::getModule('user');
        $this->assertEquals('Modules\User', $module->getNamespace());
    }

    public function testAmountOfUserModels()
    {
        $module = Api::getModule('user');

        $this->assertEquals(1, $module->getModels()->amount());
    }
}
