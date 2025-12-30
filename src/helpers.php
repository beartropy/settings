<?php

if (! function_exists('setting')) {
    /**
     * Get or set a setting value.
     *
     * @param  string|null  $key
     * @param  mixed  $default
     * @return mixed|\Beartropy\Settings\Contracts\SettingsStorage
     */
    function setting($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('beartropy-settings');
        }

        return app('beartropy-settings')->get($key, $default);
    }
}
