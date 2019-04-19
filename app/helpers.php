<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

if (! function_exists('classActivePath')) {
    function classActivePath($path)
    {
        return Request::is($path) ? ' class="active"' : '';
    }
}

if (! function_exists('classActiveSegment')) {
    function classActiveSegment($segment, $value)
    {
        if (! is_array($value)) {
            return Request::segment($segment) == $value ? ' class="active"' : '';
        }
        foreach ($value as $v) {
            if (Request::segment($segment) == $v) {
                return ' class="active"';
            }
        }

        return '';
    }
}

if (! function_exists('classActiveOnlyPath')) {
    function classActiveOnlyPath($path)
    {
        return Request::is($path) ? ' active' : '';
    }
}

if (! function_exists('classActiveOnlySegment')) {
    function classActiveOnlySegment($segment, $value)
    {
        if (! is_array($value)) {
            return Request::segment($segment) == $value ? ' active' : '';
        }
        foreach ($value as $v) {
            if (Request::segment($segment) == $v) {
                return ' active';
            }
        }

        return '';
    }
}
