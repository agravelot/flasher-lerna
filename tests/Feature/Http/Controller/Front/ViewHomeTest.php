<?php

namespace Tests\Feature\Http\Controller\Front;

use Tests\TestCase;

class ViewHomeTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function testBasicTest(): void
    {
        $response = $this->get('/');

        $response->assertOk();
    }
}
