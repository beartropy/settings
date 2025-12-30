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

        // If key doesn't have a dot, we assume group is default or we need to look it up from config if we want to enforce schema.
        // For now, we'll try to find existing or create new.
        // To properly support "group.key", we might need to parse.
        // Let's assume the key passed here IS the key in the database (which might contain dots or might not).
        // The prompt says "group.key", so usually `group` is a column and `key` is a column.
        // However, standard key-value stores usually flatten this.
        // Use the prompt's `key` field as unique.
        // We will store the full key in the `key` column for simplicity of retrieval, 
        // OR we split it. The prompt asked for: "key (string), group (string)".
        // Let's assume the argument `$key` in `get('group.key')` maps to `group`='group' and `key`='key'.

        // Re-reading prompt: "get('group.key', $default)".
        // So we need to parse the dot notation.

        $parts = explode('.', $key);
        $groupStr = count($parts) > 1 ? array_shift($parts) : 'default';
        $keyStr = implode('.', $parts);

        $setting = Setting::firstOrNew(['key' => $keyStr, 'group' => $groupStr]);

        // If it's new, we might need to infer type or set it to string default. 
        // For dynamic setting, let's guess type from value if it's new.
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
