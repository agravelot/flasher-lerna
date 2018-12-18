<?php

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
        $method = strtolower($method);
        if (! in_array($method, ['get', 'post', 'put', 'update', 'delete'])) {
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
