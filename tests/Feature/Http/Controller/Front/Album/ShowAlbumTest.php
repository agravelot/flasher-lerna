<?php

namespace Tests\Feature\Http\Controller\Front\Album;

use App\Models\Album;
use App\Models\Category;
use App\Models\PublicAlbum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class ShowAlbumTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_a_published_album()
    {
        $album = factory(PublicAlbum::class)->states(['withUser'])->create([
            'title' => 'Test title',
            'body' => 'Some test body, this is a good day!',
        ]);

        $response = $this->showAlbum($album);

        $response->assertOk()
            ->assertSee('Test title')
            ->assertSee('Some test body, this is a good day!');
    }

    private function showAlbum(Album $album): TestResponse
    {
        return $this->json('get', '/api/albums/'.$album->slug);
    }

    public function test_guest_can_view_a_published_album_with_category()
    {
        $category = factory(Category::class)->create([
            'name' => 'Category name',
        ]);

        $album = factory(PublicAlbum::class)->states(['withUser'])->create([
            'title' => 'Test title',
            'body' => 'Some test body, this is a good day!',
        ]);
        $album->categories()->attach($category);

        $response = $this->showAlbum($album);

        $response->assertOk()
            ->assertSee('Test title')
            ->assertSee('Some test body, this is a good day!')
            ->assertSee('Category name');
    }

    public function test_guest_can_view_a_published_album_with_two_categories()
    {
        $categoryA = factory(Category::class)->create([
            'name' => 'Category name',
        ]);

        $categoryB = factory(Category::class)->create([
            'name' => 'Category name two',
        ]);

        $album = factory(PublicAlbum::class)->states(['withUser'])->create([
            'title' => 'Test title',
            'body' => 'Some test body, this is a good day!',
        ]);

        $album->categories()->attach([$categoryA->id, $categoryB->id]);

        $response = $this->showAlbum($album);

        $response->assertOk()
            ->assertSee('Test title')
            ->assertSee('Some test body, this is a good day!')
            ->assertSee('Category name')
            ->assertSee('Category name two');
    }

    public function test_guest_cannot_view_unpublished_album_listing()
    {
        $album = factory(Album::class)->states(['unpublished', 'passwordLess', 'withUser'])->create();

        $response = $this->showAlbum($album);

        $response->assertStatus(404);
    }

    public function test_bad_slug_redirect_page_not_found()
    {
        $response = $this->get('/albums/some-random-slug');

        $response->assertStatus(404);
    }

    public function test_album_has_og_tags_without_image()
    {
        $album = factory(PublicAlbum::class)->state('withUser')->create();

        $response = $this->get("/albums/{$album->slug}");
        $locale = App::getLocale();

        $response->assertSee("<meta property=\"og:title\" content=\"{$album->title}\"/>")
            ->assertSee("<meta property=\"article:author\" content=\"{$album->user->name}\"/>")
            ->assertSee('<meta property="article:section" content="Photography"/>')
            ->assertSee("<meta property=\"article:modified_time\" content=\"{$album->updated_at->toIso8601String()}\"/>")
            ->assertSee("<meta property=\"article:published_time\" content=\"{$album->published_at->toIso8601String()}\"/>")
            ->assertSee("<meta property=\"og:locale\" content=\"{$locale}\"/>");

        foreach ($album->categories()->pluck('name') as $categoryName) {
            $response->assertSee("<meta property=\"article:tag\" content=\"{{ $categoryName }}\"/>");
        }

        $response->assertDontSee('<meta property="og:image" content=');
    }

    public function test_album_has_og_tags_with_image()
    {
        /** @var \App\Models\Album $album */
        $album = factory(PublicAlbum::class)->state('withUser')->create();
        $album->addPicture(UploadedFile::fake()->image('test.png'));
        $response = $this->get("/albums/{$album->slug}");
        $locale = App::getLocale();

        $response->assertSee("<meta property=\"og:title\" content=\"{$album->title}\"/>")
            ->assertSee("<meta property=\"article:author\" content=\"{$album->user->name}\"/>")
            ->assertSee('<meta property="article:section" content="Photography"/>')
            ->assertSee("<meta property=\"article:modified_time\" content=\"{$album->updated_at->toIso8601String()}\"/>")
            ->assertSee("<meta property=\"article:published_time\" content=\"{$album->published_at->toIso8601String()}\"/>")
            ->assertSee("<meta property=\"og:locale\" content=\"{$locale}\"/>")
            ->assertSee("<meta property=\"og:image\" content=\"{$album->cover->getUrl('thumb')}\"/>")
            ->assertSee("<meta property=\"og:image:secure_url\" content=\"{$album->cover->getUrl('thumb')}\"/>");

        foreach ($album->categories()->pluck('name') as $categoryName) {
            $response->assertSee("<meta property=\"article:tag\" content=\"{{ $categoryName }}\"/>");
        }
    }
}
