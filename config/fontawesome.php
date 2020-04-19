<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Component name
    |--------------------------------------------------------------------------
    |
    | The name of the component to use in blade templates.
    |
    */

    'component_name' => 'fa',

    /*
    |--------------------------------------------------------------------------
    | Path to svg libraries
    |--------------------------------------------------------------------------
    |
    | This is the absolute path to the folder where the icon libraries
    | containing svg files is located.
    |
    */

    'icon_path' => \base_path('/vendor/fortawesome/font-awesome/svgs/'),

    /*
    |--------------------------------------------------------------------------
    | Font Awesome Libraries
    |--------------------------------------------------------------------------
    |
    | These are the font awesome libraries that will be available.
    | The order defined here is the order the libraries will be explored in
    | when using the @fa() directive.
    |
    */

    'libraries' => [
        'regular',
        'brands',
        'solid',
    ],

    /*
    |--------------------------------------------------------------------------
    | Use svg href attribute
    |--------------------------------------------------------------------------
    |
    | The href attribute can be used to link to an already existing svg in the
    | DOM, this way the svg code only needs to be loaded once if used multiple
    | times.
    |
    | Only very old browser might not support this feature:
    | https://caniuse.com/#feat=mdn-svg_elements_use_href
    |
    */

    'svg_href' => true,

    /*
    |--------------------------------------------------------------------------
    | Use Font Awesome css
    |--------------------------------------------------------------------------
    |
    | Whether to add the font awesome css to the svg tags in your dom.
    | This is recommended, but if you want full control of how your svgs look
    | it can be easier to disable this so you can style it all yourself.
    |
    */

    'font_awesome_css' => true,

];
