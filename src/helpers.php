<?php

if (! function_exists('get_setting')) {
    /**
     * Get a setting value.
     *
     * @param  string  $key     The setting key (e.g., 'group.key' or 'key').
     * @param  mixed   $default The default value if the setting does not exist.
     * @return mixed
     */
    function get_setting($key, $default = null)
    {
        return app('beartropy-settings')->get($key, $default);
    }
}

if (! function_exists('set_setting')) {
    /**
     * Set a setting value.
     *
     * @param  string  $key   The setting key (e.g., 'group.key' or 'key').
     * @param  mixed   $value The value to set.
     * @return void
     */
    function set_setting($key, $value = null)
    {
        app('beartropy-settings')->set($key, $value);
    }
}
