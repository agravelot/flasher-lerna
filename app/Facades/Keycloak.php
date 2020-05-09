<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Keycloak extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'keycloak';
    }
}
