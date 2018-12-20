<?php

namespace Tests\Feature\Http\Controller\Front\Contact;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use DatabaseMigrations;

    public function test_guest_view_nothing_to_show()
    {
        $response = $this->showContact();

        $response->assertStatus(200);
    }

    private function showContact(): TestResponse
    {
        return $this->get('/contact');
    }
}