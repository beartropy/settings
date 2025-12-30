<?php

namespace Beartropy\Settings\Contracts;

interface SettingsStorage
{
    public function get(string $key, $default = null): mixed;
    public function set(string $key, $value = null): void;
    public function has(string $key): bool;
    public function all(): array;
}
