<?php

namespace Beartropy\Settings\Services;

use Beartropy\Settings\Contracts\SettingsStorage;
use Beartropy\Settings\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class SettingsManager implements SettingsStorage
{
    protected $cacheTag = 'beartropy_settings';

    public function get(string $key, $default = null): mixed
    {
        if (! $this->databaseHasTable()) {
            return $default;
        }

        // Try to get from individual cache first
        return Cache::rememberForever("{$this->cacheTag}.setting.{$key}", function () use ($key, $default) {
            $parts = explode('.', $key);
            $groupStr = count($parts) > 1 ? array_shift($parts) : 'default';
            $keyStr = implode('.', $parts);

            $setting = Setting::where('group', $groupStr)->where('key', $keyStr)->first();

            return $setting ? $setting->value : $default;
        });
    }

    public function set(string $key, $value = null): void
    {
        if (! $this->databaseHasTable()) {
            return;
        }

        [$group,] = explode('.', $key, 2) + ['default', null];

        $parts = explode('.', $key);
        $groupStr = count($parts) > 1 ? array_shift($parts) : 'default';
        $keyStr = implode('.', $parts);

        $setting = Setting::firstOrNew(['key' => $keyStr, 'group' => $groupStr]);

        if (! $setting->exists && ! $setting->type) {
            $setting->type = $this->inferType($value);
        }

        $setting->value = $value;
        $setting->save();

        // Model events handle cache clearing
    }

    public function has(string $key): bool
    {
        return $this->get($key, '__NOT_FOUND__') !== '__NOT_FOUND__';
    }

    public function all(): array
    {
        return Cache::rememberForever("{$this->cacheTag}.settings.all", function () {
            return Setting::all()->mapWithKeys(function ($setting) {
                return ["{$setting->group}.{$setting->key}" => $setting->value];
            })->toArray();
        });
    }

    protected function databaseHasTable(): bool
    {
        // Prevent crashes during migrations or before setup
        try {
            return Schema::hasTable('beartropy_settings');
        } catch (\Throwable $e) {
            return false;
        }
    }

    protected function inferType($value): string
    {
        if (is_bool($value)) return 'boolean';
        if (is_int($value)) return 'integer';
        if (is_array($value)) return 'array';
        return 'string';
    }
}
