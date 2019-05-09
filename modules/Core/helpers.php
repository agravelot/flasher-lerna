<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

if (! function_exists('settings')) {
    /**
     * Get the settings manager instance.
     *
     * @return Modules\Core\SettingsManager
     */
    function settings()
    {
        return new Modules\Core\SettingsManager();
    }
}
