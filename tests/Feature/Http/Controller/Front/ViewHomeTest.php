<?php

namespace Tests\Feature;

use Tests\TestCase;

class ViewHomeTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertOk();
    }
}
