<?php

namespace Beartropy\Settings\Contracts;

/**
 * Interface SettingsStorage.
 *
 * Defines the contract for storing and retrieving settings.
 */
interface SettingsStorage
{
    /**
     * Get a setting value.
     *
     * @param  string  $key     The setting key.
     * @param  mixed   $default The default value.
     * @return mixed
     */
    public function get(string $key, $default = null): mixed;

    /**
     * Set a setting value.
     *
     * @param  string  $key   The setting key.
     * @param  mixed   $value The setting value.
     * @return void
     */
    public function set(string $key, $value = null): void;

    /**
     * Check if a setting exists.
     *
     * @param  string $key The setting key.
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Get all settings.
     *
     * @return array
     */
    public function all(): array;
}
