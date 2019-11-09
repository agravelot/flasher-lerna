<?php

namespace Tests\Unit\Models;

use Carbon\Carbon;
use App\Models\Album;
use App\Models\Media;
use Tests\ModelTestCase;
use App\Scope\PublicScope;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AlbumTest extends ModelTestCase
{
    use WithFaker, RefreshDatabase;

    public function testRouteKeyName(): void
    {
        $album = new Album();
        $slug = $this->faker->slug;
        $routeKey = $album->getRouteKeyName();
        $album->$$routeKey = $slug;

        $this->assertSame($$routeKey, $slug);
    }

    public function testSlugAsRouteKeyName(): void
    {
        $album = new Album();
        $routeKey = $album->getRouteKeyName();

        $excepted = 'slug';

        $this->assertSame($excepted, $routeKey);
    }

    public function testSlugSourceAsTitle(): void
    {
        $album = new Album();
        $slugSource = $album->sluggable()['slug']['source'];

        $excepted = 'title';

        $this->assertSame($excepted, $slugSource);
    }

    public function testModelConfiguration(): void
    {
        $this->runConfigurationAssertions(new Album(), [
            'title', 'slug', 'body', 'published_at', 'user_id', 'private', 'notify_users_on_published',
        ], [], ['*'], [], ['id' => 'int', 'private' => 'bool'], [
            'published_at',
            'updated_at',
            'created_at',
        ]);
    }

    public function testBelongsToManyAlbumsRelationship(): void
    {
        $album = new Album();
        $relation = $album->cosplayers();

        $this->assertBelongsToManyRelation($relation, $album, new Album(), 'album_id');
    }

    public function test_album_is_public(): void
    {
        $album = factory(Album::class)->states(['published', 'passwordLess'])->make();

        $this->assertTrue($album->isPublic());
    }

    public function test_album_is_private_when_not_published(): void
    {
        $album = factory(Album::class)->states(['unpublished', 'passwordLess'])->make();

        $this->assertFalse($album->isPublic());
    }

    public function test_album_is_private_when_password_is_defined(): void
    {
        $album = factory(Album::class)->states(['published', 'password'])->make();

        $this->assertFalse($album->isPublic());
    }

    public function test_album_is_private_when_password_is_defined_and_not_published(): void
    {
        $album = factory(Album::class)->states(['unpublished', 'password'])->make();

        $this->assertFalse($album->isPublic());
    }

    public function test_album_without_password_is_passwordLess(): void
    {
        /** @var Album $album */
        $album = factory(Album::class)->states(['passwordLess'])->make();

        $this->assertTrue($album->isPasswordLess());
    }

    public function test_album_with_password_is_not_passwordLess(): void
    {
        /** @var Album $album */
        $album = factory(Album::class)->states(['password'])->make();

        $this->assertFalse($album->isPasswordLess());
    }

    public function test_set_published_at_with_true_is_now(): void
    {
        $knownDate = Carbon::create(2018, 5, 21, 12);
        Carbon::setTestNow($knownDate);
        $album = factory(Album::class)->states(['published'])->make();

        $this->assertSame($knownDate->format('Y-m-d H:i:s'), $album->published_at->format('Y-m-d H:i:s'));
    }

    public function test_set_published_at_with_date(): void
    {
        $knownDate = Carbon::create(2018, 5, 21, 12);
        Carbon::setTestNow($knownDate);
        $album = factory(Album::class)->make();
        $album->published_at = $knownDate;

        $this->assertSame($knownDate->format('Y-m-d H:i:s'), $album->published_at->format('Y-m-d H:i:s'));
    }

    public function test_album_with_a_published_at_date_are_published(): void
    {
        $publishedAlbums = factory(Album::class, 2)->states(['published', 'withUser'])->create();

        $albums = Album::all();

        $this->assertSame(2, $albums->count());
        $this->assertTrue($albums->contains($publishedAlbums->get(0)));
        $this->assertTrue($albums->contains($publishedAlbums->get(1)));
    }

    public function test_album_with_unpublished_are_not_visible(): void
    {
        factory(Album::class, 2)->states(['unpublished', 'passwordLess', 'withUser'])->create();

        $albums = Album::all();

        $this->assertSame(2, Album::count());
        $this->assertTrue($albums->contains($albums->get(0)));
        $this->assertTrue($albums->contains($albums->get(1)));
    }

    public function test_album_with_a_password_are_not_visible(): void
    {
        factory(Album::class, 2)->states(['published', 'password', 'withUser'])->create();

        $albums = Album::all();

        $this->assertSame(2, Album::count());
        $this->assertTrue($albums->contains($albums->get(0)));
        $this->assertTrue($albums->contains($albums->get(1)));
    }

    public function test_album_with_a_published_at_date_and_password_are_unpublished(): void
    {
        factory(Album::class, 2)->states(['published', 'password', 'withUser'])->create();

        $albums = Album::all();

        $this->assertSame(2, $albums->count());
    }

    public function test_count_four_published_albums_should_be_four(): void
    {
        $publishedAlbums = factory(Album::class, 4)->states(['published', 'passwordLess', 'withUser'])->create();

        $albums = Album::all();

        $this->assertSame(4, $albums->count());
        $this->assertTrue($albums->contains($publishedAlbums->get(0)));
        $this->assertTrue($albums->contains($publishedAlbums->get(1)));
        $this->assertTrue($albums->contains($publishedAlbums->get(2)));
        $this->assertTrue($albums->contains($publishedAlbums->get(3)));
    }

    public function test_count_four_unpublished_albums_should_be_zero(): void
    {
        factory(Album::class, 4)->states(['unpublished', 'passwordLess', 'withUser'])->create();

        $albums = Album::all();

        $this->assertSame(4, Album::count());
        $this->assertTrue($albums->contains($albums->get(0)));
        $this->assertTrue($albums->contains($albums->get(1)));
        $this->assertTrue($albums->contains($albums->get(2)));
        $this->assertTrue($albums->contains($albums->get(3)));
    }

    public function test_album_with_a_published_at_date_are_published_without_public_criteria(): void
    {
        $publishedAlbums = factory(Album::class, 2)->states(['published', 'withUser'])->create();

        $albums = Album::withoutGlobalScope(PublicScope::class)->get();

        $this->assertTrue($albums->contains($publishedAlbums->get(0)));
        $this->assertTrue($albums->contains($publishedAlbums->get(1)));
    }

    public function test_album_with_password_are_unpublished_without_public_criteria(): void
    {
        $publishedAlbums = factory(Album::class, 2)->states(['unpublished', 'withUser'])->create();

        $albums = Album::withoutGlobalScope(PublicScope::class)->get();

        $this->assertTrue($albums->contains($publishedAlbums->get(0)));
        $this->assertTrue($albums->contains($publishedAlbums->get(1)));
    }

    public function test_album_with_a_published_at_date_and_password_are_unpublished_without_public_criteria(): void
    {
        $publishedAlbums = factory(Album::class, 2)->states(['published', 'password', 'withUser'])->create();

        $albums = Album::withoutGlobalScope(PublicScope::class)->get();

        $this->assertTrue($albums->contains($publishedAlbums->get(0)));
        $this->assertTrue($albums->contains($publishedAlbums->get(1)));
    }

    public function test_count_four_published_albums_should_be_four_without_public_criteria(): void
    {
        $publishedAlbums = factory(Album::class, 4)->states(['published', 'passwordLess', 'withUser'])->create();

        $albums = Album::withoutGlobalScope(PublicScope::class)->get();

        $this->assertTrue($albums->contains($publishedAlbums->get(0)));
        $this->assertTrue($albums->contains($publishedAlbums->get(1)));
        $this->assertTrue($albums->contains($publishedAlbums->get(2)));
        $this->assertTrue($albums->contains($publishedAlbums->get(3)));
    }

    public function test_count_four_unpublished_albums_should_be_zero_without_public_criteria(): void
    {
        $publishedAlbums = factory(Album::class, 4)->states(['unpublished', 'passwordLess', 'withUser'])->create();

        $albums = Album::withoutGlobalScope(PublicScope::class)->get();

        $this->assertTrue($albums->contains($publishedAlbums->get(0)));
        $this->assertTrue($albums->contains($publishedAlbums->get(1)));
        $this->assertTrue($albums->contains($publishedAlbums->get(2)));
        $this->assertTrue($albums->contains($publishedAlbums->get(3)));
    }

    public function test_mage_first_in_is_the_album_cover(): void
    {
        /** @var Album $album */
        $album = factory(Album::class)->states('withUser')->create();
        $exceptedName = 'shouldBeTheCover.jpg';

        $album->addPicture(UploadedFile::fake()->image($exceptedName));
        $album->addPicture(UploadedFile::fake()->image('second.jpg'));
        $album->addPicture(UploadedFile::fake()->image('last.jpg'));

        /** @var Media $cover */
        $cover = $album->cover;
        $this->assertSame($exceptedName, $cover->name.'.'.$cover->extension);
    }
}
