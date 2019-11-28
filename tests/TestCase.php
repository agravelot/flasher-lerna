<?php

namespace Tests;

use Closure;
use Exception;
use App\Models\User;
use App\Exceptions\Handler;
use InvalidArgumentException;
use Illuminate\Support\Fluent;
use Laravel\Passport\Passport;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\App;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Database\Schema\SQLiteBuilder;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->hotfixSqlite();
    }

    public function hotfixSqlite(): void
    {
        Connection::resolverFor('sqlite', function ($connection, $database, $prefix, $config) {
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

    protected function setUp(): void
    {
        parent::setUp();
        App::setLocale('en');
        $this->withoutMiddleware(VerifyCsrfToken::class);
        // Workaround for resource wrapping issue during tests
        // https://github.com/laravel/framework/issues/26021
        Resource::$wrap = 'data';
    }

    protected function actingAsAdminNotStored(): void
    {
        $admin = factory(User::class)->state('admin')->make();
        $this->actingAs($admin);
    }

    protected function actingAsAdmin(): void
    {
        $admin = factory(User::class)->state('admin')->create();
        $this->actingAs($admin);
        Passport::actingAs($admin, ['*']);
    }

    protected function actingAsUser(): void
    {
        $user = factory(User::class)->state('user')->create();
        $this->actingAs($user);
        Passport::actingAs($user, ['*']);
    }

    protected function assertAuthenticationRequired($uri, $method = 'get', $redirect = '/login'): void
    {
        $method = mb_strtolower($method);
        if (! \in_array($method, ['get', 'post', 'put', 'update', 'delete'], true)) {
            throw new InvalidArgumentException('Invalid method: '.$method);
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

    protected function disableExceptionHandling(): void
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
