<?php

if (! function_exists('get_setting')) {
    /**
     * Get a setting value.
     *
     * @param  string  $key
     * @param  mixed  $default
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
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    function set_setting($key, $value = null)
    {
        app('beartropy-settings')->set($key, $value);
    }
}
