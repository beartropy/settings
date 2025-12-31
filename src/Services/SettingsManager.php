<?php

namespace Beartropy\Settings\Services;

use Beartropy\Settings\Contracts\SettingsStorage;
use Beartropy\Settings\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

/**
 * Class SettingsManager.
 *
 * Manages the retrieval and storage of settings using the database and cache.
 */
class SettingsManager implements SettingsStorage
{
    /**
     * The cache tag used for settings.
     *
     * @var string
     */
    protected $cacheTag = 'beartropy_settings';

    /**
     * Get a setting value.
     *
     * @param  string  $key     The setting key.
     * @param  mixed   $default The default value.
     * @return mixed
     */
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

    /**
     * Set a setting value.
     *
     * @param  string  $key   The setting key.
     * @param  mixed   $value The setting value.
     * @return void
     */
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

    /**
     * Check if a setting exists.
     *
     * @param  string $key The setting key.
     * @return bool
     */
    public function has(string $key): bool
    {
        return $this->get($key, '__NOT_FOUND__') !== '__NOT_FOUND__';
    }

    /**
     * Get all settings.
     *
     * @return array
     */
    public function all(): array
    {
        return Cache::rememberForever("{$this->cacheTag}.settings.all", function () {
            return Setting::all()->mapWithKeys(function ($setting) {
                return ["{$setting->group}.{$setting->key}" => $setting->value];
            })->toArray();
        });
    }

    /**
     * Check if the settings table exists in the database.
     *
     * @return bool
     */
    protected function databaseHasTable(): bool
    {
        // Prevent crashes during migrations or before setup
        try {
            return Schema::hasTable('beartropy_settings');
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Infer the type of a value.
     *
     * @param  mixed  $value
     * @return string
     */
    protected function inferType($value): string
    {
        if (is_bool($value)) return 'boolean';
        if (is_int($value)) return 'integer';
        if (is_array($value)) return 'array';
        return 'string';
    }
}
