<?php

namespace Tests\Unit;

use Lunaweb\RedisMock\Providers\RedisMockServiceProvider;

trait RedisTestSetUp
{

    protected function getEnvironmentSetUp($app)
    {

        $app['config']->set('app.debug', true);
        $app['config']->set('database.redis.client', 'mock');

        $app->register(RedisMockServiceProvider::class);
    }

}
