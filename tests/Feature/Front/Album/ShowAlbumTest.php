<?php

namespace Tests\Feature;

use App\Models\Album;
use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class ShowAlbumTest extends TestCase
{
    use DatabaseMigrations;

    public function test_guest_can_view_a_published_album()
    {
        $album = factory(Album::class)->states(['published', 'passwordLess', 'withUser'])->create([
            'title' => 'Test title',
            'body' => 'Some test body, this is a good day!',
        ]);

        $response = $this->showAlbum($album);

        $response->assertStatus(200);
        $response->assertSee('Test title');
        $response->assertSee('Some test body, this is a good day!');
    }

    public function test_guest_can_view_a_published_album_with_category()
    {
        $category = factory(Category::class)->create([
            'name' => 'Category name',
        ]);

        $album = factory(Album::class)->states(['published', 'passwordLess', 'withUser'])->create([
            'title' => 'Test title',
            'body' => 'Some test body, this is a good day!',
        ]);
        $album->categories()->attach($category);

        $response = $this->showAlbum($album);

        $response->assertStatus(200);
        $response->assertSee('Test title');
        $response->assertSee('Some test body, this is a good day!');
        $response->assertSee('Category name');
    }

    public function test_guest_can_view_a_published_album_with_two_categories()
    {
        $categoryA = factory(Category::class)->create([
            'name' => 'Category name',
        ]);

        $categoryB = factory(Category::class)->create([
            'name' => 'Category name two',
        ]);

        $album = factory(Album::class)->states(['published', 'passwordLess', 'withUser'])->create([
            'title' => 'Test title',
            'body' => 'Some test body, this is a good day!',
        ]);

        $album->categories()->attach([$categoryA->id, $categoryB->id]);

        $response = $this->showAlbum($album);

        $response->assertStatus(200);
        $response->assertSee('Test title');
        $response->assertSee('Some test body, this is a good day!');
        $response->assertSee('Category name');
        $response->assertSee('Category name two');
    }

    public function test_guest_cannot_view_unpublished_album_listing()
    {
        $album = factory(Album::class)->states(['unpublished', 'passwordLess', 'withUser'])->create();

        $response = $this->showAlbum($album);

        $response->assertStatus(403);
    }

    public function test_bad_slug_redirect_page_not_found()
    {
        $response = $this->get('/albums/same-random-slug');

        $response->assertStatus(404);
    }

    private function showAlbum(Album $album): TestResponse
    {
        return $this->get('/albums/' . $album->slug);
    }
}
