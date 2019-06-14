<?php

namespace Base\Tests;

use Base\Cache\ModelCache;
use Base\Contracts\Abstracts\TestCase;
use Modules\Account\Models\Account;

class CacheObserverTest extends TestCase
{
    public function testCache()
    {
        $user = $this->getActingUser();
        $user->fresh();
        unset($user->role_ids);
        unset($user->unreadNotifications);
        $this->assertEquals($user->toArray(), $user::cache()->find($user->id, false)->toArray());
        $this->assertEquals($user->toArray(), $user::cache()->findBy('identity_id', $user->identity_id, false)->toArray());
    }

    public function testCacheSpeed()
    {
        $model = Account::create(['testthisshit' => 5]);
        \Cache::forever('testmodel', $model);

        $time_db_start = microtime(true);

        for ($i = 0; $i < 1000; $i++) {
            Account::find($model->id);
        }
        $time_db_end = microtime(true);

        $dbTime = $time_db_end - $time_db_start;

        $time_cache_start = microtime(true);

        for ($i = 0; $i < 1000; $i++) {
            \Cache::get('testmodel');
        }
        $time_cache_end = microtime(true);

        $cacheTime = $time_cache_end - $time_cache_start;

        $this->assertGreaterThan($cacheTime, $dbTime);
    }

    public function testClearModelsCache()
    {
        $user = $this->getActingUser();

        $this->assertNotNull($user::cache()->find($user->id));
        ModelCache::clearAll();
        $this->assertNull($user::cache()->find($user->id));
    }

    public function testClearSpecificModelCache()
    {
        $user = $this->getActingUser();
        $this->assertNotNull($user::cache()->find($user->id));
        $user::cache()->clearModelCache();
        $this->assertNull($user::cache()->find($user->id));
    }
}
