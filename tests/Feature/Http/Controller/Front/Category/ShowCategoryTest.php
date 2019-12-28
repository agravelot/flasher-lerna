<?php

namespace Tests\Feature\Front\Category;

use App\Models\Album;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class ShowCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_a_category()
    {
        $category = factory(Category::class)->create();

        $response = $this->showCategory($category);

        $response->assertOk()
            ->assertSee($category->title)
            ->assertSee($category->description);
    }

    private function showCategory(Category $category): TestResponse
    {
        return $this->get('/categories/'.$category->slug);
    }

    public function test_guest_can_view_a_category_with_albums()
    {
        /** @var Category $category */
        $category = factory(Category::class)->create();
        $albums = factory(Album::class, 2)->states('published', 'passwordLess', 'withUser')->create();
        $category->albums()->attach($albums);

        $response = $this->showCategory($category);

        $response->assertOk();
        $response->assertSee($category->name);
        $response->assertSee($category->description);
        $response->assertDontSee('Nothing to show');
        $response->assertSee($albums->get(0)->title);
        $response->assertSee($albums->get(1)->title);
    }

    public function test_bad_slug_redirect_page_not_found()
    {
        $response = $this->get('/categories/some-random-slug');

        $response->assertStatus(404);
    }

    public function test_guest_cant_see_unpublished_albums_of_a_category()
    {
        /** @var Category $category */
        $category = factory(Category::class)->create();
        $album = factory(Album::class)->states('unpublished', 'passwordLess', 'withUser')->create();
        $category->albums()->attach($album);

        $response = $this->showCategory($category);

        $response->assertOk()
            ->assertSee($category->title)
            ->assertSee($category->description)
            ->assertDontSee($album->title);
    }
}
