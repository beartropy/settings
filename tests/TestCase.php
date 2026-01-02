<?php

namespace Beartropy\Settings\Tests;

use Beartropy\Settings\BeartropySettingsServiceProvider;
use Illuminate\Encryption\Encrypter;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        // Load migrations from the package
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    protected function getPackageProviders($app)
    {
        return [
            BeartropySettingsServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('app.key', 'base64:' . base64_encode(Encrypter::generateKey(config('app.cipher'))));
    }
}
