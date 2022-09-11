<?php

namespace YasserBenaioua\Chargily\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use YasserBenaioua\Chargily\ChargilyServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            ChargilyServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        $migration = include __DIR__.'/../database/migrations/create_chargily_webhook_calls_table.php.stub';
        $migration->up();
    }
}
