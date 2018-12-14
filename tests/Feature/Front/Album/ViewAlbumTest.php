<?php

namespace Tests\Feature;

use App\Models\Album;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ViewAlbumTest extends TestCase
{
    use DatabaseMigrations;

    public function test_user_can_view_a_published_album()
    {
        $user = factory(User::class)->create();

        $category = factory(Category::class)->create([
            'name' => 'Category name',
        ]);

        /** @var Album $album */
        $album = factory(Album::class)->states(['published', 'passwordLess'])->create([
            'title' => 'Test title',
            'body' => 'Some test body, this is a good day!',
            'user_id' => $user->id,
        ]);
        $album->categories()->attach($category);

        $response = $this->get('/albums/'.$album->slug);

        $response->assertStatus(200);
        $response->assertSee('Test title');
        $response->assertSee('Some test body, this is a good day!');
        $response->assertSee('Category name');
    }

    public function test_user_cannot_view_private_album_listing()
    {
        $user = factory(User::class)->create();

        /** @var Album $album */
        $album = factory(Album::class)->states(['unpublished', 'passwordLess'])->create([
            'user_id' => $user->id,
        ]);

        $response = $this->get('/albums/'.$album->slug);

        $response->assertStatus(404);
    }
}
