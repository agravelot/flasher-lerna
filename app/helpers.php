<?php

if (! function_exists('string_to_color')) {
    function string_to_color(string $str): string
    {
        return '#00000';
    }
}

if (! function_exists('settings')) {
    /**
     * Get the settings manager instance.
     *
     * @return App\SettingsManager
     */
    function settings(): \App\SettingsManager
    {
        return app('App\SettingManager');
    }
}
