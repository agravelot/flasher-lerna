<?php

namespace Tests\Feature;

use App\Models\Album;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ViewAlbumTest extends TestCase
{
    use DatabaseMigrations;

    public function test_guest_can_view_a_published_album()
    {
        $user = factory(User::class)->create();

        $album = factory(Album::class)->states(['published', 'passwordLess'])->create([
            'title' => 'Test title',
            'body' => 'Some test body, this is a good day!',
            'user_id' => $user->id,
        ]);

        $response = $this->get('/albums/' . $album->slug);

        $response->assertStatus(200);
        $response->assertSee('Test title');
        $response->assertSee('Some test body, this is a good day!');
    }

    public function test_guest_can_view_a_published_album_with_category()
    {
        $user = factory(User::class)->create();

        $category = factory(Category::class)->create([
            'name' => 'Category name',
        ]);

        $album = factory(Album::class)->states(['published', 'passwordLess'])->create([
            'title' => 'Test title',
            'body' => 'Some test body, this is a good day!',
            'user_id' => $user->id,
        ]);
        $album->categories()->attach($category);

        $response = $this->get('/albums/' . $album->slug);

        $response->assertStatus(200);
        $response->assertSee('Test title');
        $response->assertSee('Some test body, this is a good day!');
        $response->assertSee('Category name');
    }

    public function test_guest_can_view_a_published_album_with_two_categories()
    {
        $user = factory(User::class)->create();

        $categoryA = factory(Category::class)->create([
            'name' => 'Category name',
        ]);

        $categoryB = factory(Category::class)->create([
            'name' => 'Category name two',
        ]);

        $album = factory(Album::class)->states(['published', 'passwordLess'])->create([
            'title' => 'Test title',
            'body' => 'Some test body, this is a good day!',
            'user_id' => $user->id,
        ]);

        $album->categories()->attach([$categoryA->id, $categoryB->id]);

        $response = $this->get('/albums/' . $album->slug);

        $response->assertStatus(200);
        $response->assertSee('Test title');
        $response->assertSee('Some test body, this is a good day!');
        $response->assertSee('Category name');
        $response->assertSee('Category name two');
    }

    public function test_guest_cannot_view_unpublished_album_listing()
    {
        $user = factory(User::class)->create();

        $album = factory(Album::class)->states(['unpublished', 'passwordLess'])->create([
            'user_id' => $user->id,
        ]);

        $response = $this->get('/albums/' . $album->slug);

        $response->assertStatus(403);
    }

    public function test_guest_cannot_view_album_with_password_listing()
    {
        $user = factory(User::class)->create();

        $album = factory(Album::class)->states(['published', 'password'])->create([
            'user_id' => $user->id,
        ]);

        $response = $this->get('/albums/' . $album->slug);

        $response->assertStatus(403);
    }
}
