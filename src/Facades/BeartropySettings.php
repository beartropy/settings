<?php

namespace Beartropy\Settings\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed get(string $key, $default = null)
 * @method static void set(string $key, $value = null)
 * @method static bool has(string $key)
 *
 * @see \Beartropy\Settings\Services\SettingsManager
 */
class BeartropySettings extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'beartropy-settings';
    }
}
