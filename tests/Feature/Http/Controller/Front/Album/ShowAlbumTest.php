<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controller\Front\Album;

use App\Models\Album;
use App\Models\Category;
use App\Models\PublicAlbum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ShowAlbumTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_a_published_album(): void
    {
        $album = factory(PublicAlbum::class)->create([
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

    public function test_guest_can_view_a_published_album_with_category(): void
    {
        $category = factory(Category::class)->create([
            'name' => 'Category name',
        ]);

        $album = factory(PublicAlbum::class)->create([
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

    public function test_guest_can_view_a_published_album_with_two_categories(): void
    {
        $categoryA = factory(Category::class)->create([
            'name' => 'Category name',
        ]);

        $categoryB = factory(Category::class)->create([
            'name' => 'Category name two',
        ]);

        $album = factory(PublicAlbum::class)->create([
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

    public function test_guest_cannot_view_unpublished_album_listing(): void
    {
        $album = factory(Album::class)->states(['unpublished', 'passwordLess'])->create();

        $response = $this->showAlbum($album);

        $response->assertStatus(404);
    }

    public function test_bad_slug_redirect_page_not_found(): void
    {
        $response = $this->get('/albums/some-random-slug');

        $response->assertStatus(404);
    }

    public function test_album_has_og_tags_without_image(): void
    {
        $album = factory(PublicAlbum::class)->create();

        $response = $this->get("/albums/{$album->slug}");
        $locale = App::getLocale();

        $response->assertSee("<meta property=\"og:title\" content=\"{$album->title}\"/>", false)
            ->assertSee('<meta property="article:author" content="JKanda"/>', false)
            ->assertSee('<meta property="article:section" content="Photography"/>', false)
            ->assertSee("<meta property=\"article:modified_time\" content=\"{$album->updated_at->toIso8601String()}\"/>", false)
            ->assertSee("<meta property=\"article:published_time\" content=\"{$album->published_at->toIso8601String()}\"/>", false)
            ->assertSee("<meta property=\"og:locale\" content=\"{$locale}\"/>", false);

        foreach ($album->categories()->pluck('name') as $categoryName) {
            $response->assertSee("<meta property=\"article:tag\" content=\"{{ $categoryName }}\"/>", false);
        }

        $response->assertDontSee('<meta property="og:image" content=');
    }

    public function test_album_has_og_tags_with_image(): void
    {
        /** @var \App\Models\Album $album */
        $album = factory(PublicAlbum::class)->create();
        $album->addPicture(UploadedFile::fake()->image('test.png'));
        $response = $this->get("/albums/{$album->slug}");
        $locale = App::getLocale();

        $response->assertSee("<meta property=\"og:title\" content=\"{$album->title}\"/>", false)
            ->assertSee('<meta property="article:author" content="JKanda"/>', false)
            ->assertSee('<meta property="article:section" content="Photography"/>', false)
            ->assertSee("<meta property=\"article:modified_time\" content=\"{$album->updated_at->toIso8601String()}\"/>", false)
            ->assertSee("<meta property=\"article:published_time\" content=\"{$album->published_at->toIso8601String()}\"/>", false)
            ->assertSee("<meta property=\"og:locale\" content=\"{$locale}\"/>", false)
            ->assertSee("<meta property=\"og:image\" content=\"{$album->cover->getUrl('thumb')}\"/>", false)
            ->assertSee("<meta property=\"og:image:secure_url\" content=\"{$album->cover->getUrl('thumb')}\"/>", false);

        foreach ($album->categories()->pluck('name') as $categoryName) {
            $response->assertSee("<meta property=\"article:tag\" content=\"{{ $categoryName }}\"/>", false);
        }
    }
}
