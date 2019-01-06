<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace Tests\Unit\Repositories;

use App\Criteria\PublicAlbumsCriteria;
use App\Models\Album;
use App\Models\User;
use App\Repositories\AlbumRepositoryEloquent;
use App\Repositories\Contracts\AlbumRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
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

        Auth::setUser(factory(User::class)->create());

        $this->albumRepository = new AlbumRepositoryEloquent(App::getInstance());
    }

    public function test_album_with_a_published_at_date_are_published()
    {
        $publishedAlbums = factory(Album::class, 2)->states(['published'])->create();

        $view = $this->albumRepository->all();

        $this->assertTrue($view->contains($publishedAlbums->get(0)));
        $this->assertTrue($view->contains($publishedAlbums->get(1)));
    }

    public function test_album_with_password_are_unpublished()
    {
        $publishedAlbums = factory(Album::class, 2)->states(['unpublished'])->create();

        $view = $this->albumRepository->all();

        $this->assertFalse($view->contains($publishedAlbums->get(0)));
        $this->assertFalse($view->contains($publishedAlbums->get(1)));
    }

    public function test_album_with_a_published_at_date_and_password_are_unpublished()
    {
        $publishedAlbums = factory(Album::class, 2)->states(['published', 'password'])->create();

        $view = $this->albumRepository->all();

        $this->assertFalse($view->contains($publishedAlbums->get(0)));
        $this->assertFalse($view->contains($publishedAlbums->get(1)));
    }

    public function test_count_four_published_albums_should_be_four()
    {
        factory(Album::class, 4)->states(['published', 'passwordLess'])->create();

        $count = $this->albumRepository->count();

        $this->assertSame(4, $count);
    }

    public function test_count_four_unpublished_albums_should_be_zero()
    {
        factory(Album::class, 4)->states(['unpublished', 'passwordLess'])->create();

        $count = $this->albumRepository->count();

        $this->assertSame(0, $count);
    }

    public function test_album_with_a_published_at_date_are_published_without_public_criteria()
    {
        $this->withoutPublicCriteria();
        $publishedAlbums = factory(Album::class, 2)->states(['published'])->create();

        $view = $this->albumRepository->all();

        $this->assertTrue($view->contains($publishedAlbums->get(0)));
        $this->assertTrue($view->contains($publishedAlbums->get(1)));
    }

    private function withoutPublicCriteria()
    {
        $this->albumRepository->popCriteria(PublicAlbumsCriteria::class);
    }

    public function test_album_with_password_are_unpublished_without_public_criteria()
    {
        $this->withoutPublicCriteria();
        $publishedAlbums = factory(Album::class, 2)->states(['unpublished'])->create();

        $view = $this->albumRepository->all();

        $this->assertTrue($view->contains($publishedAlbums->get(0)));
        $this->assertTrue($view->contains($publishedAlbums->get(1)));
    }

    public function test_album_with_a_published_at_date_and_password_are_unpublished_without_public_criteria()
    {
        $this->withoutPublicCriteria();
        $publishedAlbums = factory(Album::class, 2)->states(['published', 'password'])->create();

        $view = $this->albumRepository->all();

        $this->assertTrue($view->contains($publishedAlbums->get(0)));
        $this->assertTrue($view->contains($publishedAlbums->get(1)));
    }

    public function test_count_four_published_albums_should_be_four_without_public_criteria()
    {
        $this->withoutPublicCriteria();
        factory(Album::class, 4)->states(['published', 'passwordLess'])->create();

        $count = $this->albumRepository->count();

        $this->assertSame(4, $count);
    }

    public function test_count_four_unpublished_albums_should_be_zero_without_public_criteria()
    {
        $this->withoutPublicCriteria();
        factory(Album::class, 4)->states(['unpublished', 'passwordLess'])->create();

        $count = $this->albumRepository->count();

        $this->assertSame(4, $count);
    }
}
