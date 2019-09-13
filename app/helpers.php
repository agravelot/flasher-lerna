<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

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
    function settings()
    {
        return app('App\SettingManager');
    }
}
