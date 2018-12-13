<?php

namespace Tests\Unit\Repositories;

use App\Criteria\PublicAlbumsCriteria;
use App\Models\Album;
use App\Models\User;
use App\Repositories\AlbumRepositoryEloquent;
use App\Repositories\Contracts\AlbumRepository;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class AlbumRepositoryEloquentTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var AlbumRepository
     */
    private $albumRepository;

    public function setUp()
    {
        parent::setUp();

        $this->albumRepository = new AlbumRepositoryEloquent(App::getInstance());
        $this->albumRepository->pushCriteria(PublicAlbumsCriteria::class);
    }

    public function test_album_with_a_published_at_date_are_published()
    {
        $user = factory(User::class)->create();
        $publishedAlbumA = factory(Album::class)->state('published')->create(['user_id' => $user->id]);
        $publishedAlbumB = factory(Album::class)->state('published')->create(['user_id' => $user->id]);

        $publishedAlbums = $this->albumRepository->all();

        $this->assertTrue($publishedAlbums->contains($publishedAlbumA));
        $this->assertTrue($publishedAlbums->contains($publishedAlbumB));
    }

    public function test_album_with_password_are_unpublished()
    {
        $user = factory(User::class)->create();
        $publishedAlbumA = factory(Album::class)->state('password')->create(['user_id' => $user->id]);
        $publishedAlbumB = factory(Album::class)->state('password')->create(['user_id' => $user->id]);

        $publishedAlbums = $this->albumRepository->all();

        $this->assertFalse($publishedAlbums->contains($publishedAlbumA));
        $this->assertFalse($publishedAlbums->contains($publishedAlbumB));
    }

    public function test_album_with_a_published_at_date_and_password_are_unpublished()
    {
        $user = factory(User::class)->create();
        $publishedAlbumA = factory(Album::class)->state('published')->state('password')->create(['user_id' => $user->id]);
        $publishedAlbumB = factory(Album::class)->state('published')->state('password')->create(['user_id' => $user->id]);

        $publishedAlbums = $this->albumRepository->all();

        $this->assertFalse($publishedAlbums->contains($publishedAlbumA));
        $this->assertFalse($publishedAlbums->contains($publishedAlbumB));
    }
}
