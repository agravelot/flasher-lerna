<?php

namespace Tests\Feature\Front\Category;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class IndexCategoryTest extends TestCase
{
    use DatabaseMigrations;

    public function test_guest_view_nothing_to_show()
    {
        $response = $this->get('/albums');

        $response->assertStatus(200);
        $response->assertSee('Nothing to show');
    }

    public function test_guest_can_view_categories()
    {
        $categories = factory(Category::class, 2)->create();

        $response = $this->get('/categories');

        $response->assertStatus(200);
        $response->assertSee($categories->get(0)->title);
        $response->assertSee($categories->get(1)->title);
    }
}
