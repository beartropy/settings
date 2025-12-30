<?php

namespace Beartropy\Settings;

use Beartropy\Settings\Contracts\SettingsStorage;
use Beartropy\Settings\Services\SettingsManager;
use Beartropy\Settings\Livewire\SettingsManager as SettingsManagerComponent;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class BeartropySettingsServiceProvider extends ServiceProvider
{
    public function register()
    {

        $this->app->singleton('beartropy-settings', function ($app) {
            return new SettingsManager();
        });

        $this->app->bind(SettingsStorage::class, 'beartropy-settings');
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'beartropy-settings');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/beartropy-settings.php' => config_path('beartropy-settings.php'),
            ], 'beartropy-settings-config');

            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'beartropy-settings-migrations');
        }

        Livewire::component('beartropy-settings-manager', SettingsManagerComponent::class);
    }
}
