<?php

declare(strict_types=1);

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
