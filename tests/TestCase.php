<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace Tests;

use App\Exceptions\Handler;
use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use InvalidArgumentException;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function assertAuthenticationRequired($uri, $method = 'get', $redirect = '/login')
    {
        $method = mb_strtolower($method);
        if (! \in_array($method, ['get', 'post', 'put', 'update', 'delete'], true)) {
            throw new InvalidArgumentException('Invalid method: ' . $method);
        }
        // Html check
        $response = $this->$method($uri);
        $response->assertStatus(302);
        $response->assertRedirect($redirect);
        // Json check
        $method .= 'Json';
        $response = $this->$method($uri);
        $response->assertStatus(401);
    }

    protected function disableExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, new class() extends Handler {
            public function __construct()
            {
            }

            public function report(Exception $e)
            {
            }

            public function render($request, Exception $e)
            {
                throw $e;
            }
        });
    }
}
