<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace Tests;

use App\Exceptions\Handler;
use App\Models\User;
use Closure;
use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\SQLiteBuilder;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Fluent;
use InvalidArgumentException;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->hotfixSqlite();
    }

    protected function actingAsAdmin()
    {
        $admin = factory(User::class)->state('admin')->create();
        $this->actingAs($admin);
    }

    protected function actingAsUser()
    {
        $admin = factory(User::class)->state('user')->create();
        $this->actingAs($admin);
    }

    public function hotfixSqlite()
    {
        \Illuminate\Database\Connection::resolverFor('sqlite', function ($connection, $database, $prefix, $config) {
            return new class($connection, $database, $prefix, $config) extends SQLiteConnection {
                public function getSchemaBuilder()
                {
                    if ($this->schemaGrammar === null) {
                        $this->useDefaultSchemaGrammar();
                    }

                    return new class($this) extends SQLiteBuilder {
                        protected function createBlueprint($table, Closure $callback = null)
                        {
                            return new class($table, $callback) extends Blueprint {
                                public function dropForeign($index)
                                {
                                    return new Fluent();
                                }
                            };
                        }
                    };
                }
            };
        });
    }

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
