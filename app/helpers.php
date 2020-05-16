<?php

declare(strict_types=1);

if (! function_exists('settings')) {
    /**
     * Get the settings manager instance.
     */
    function settings(): App\SettingsManager
    {
        return app('App\SettingManager');
    }
}
