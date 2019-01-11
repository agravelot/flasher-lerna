<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace Tests\Feature\Http\Controller\Front\GoldenBook;

use App\Models\GoldenBookPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class IndexGoldenBookTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_see_empty_golden_book_index()
    {
        $response = $this->showGoldenBooks();

        $response->assertStatus(200);
        $response->assertSee('Nothing to show');
    }

    private function showGoldenBooks(): TestResponse
    {
        return $this->get('/goldenbook');
    }

    public function test_guest_can_see_active_golden_book_index()
    {
        $goldenBookPosts = factory(GoldenBookPost::class, 2)->state('active')->create();

        $response = $this->showGoldenBooks();

        $response->assertStatus(200);
        $response->assertDontSee('Nothing to show');
        $response->assertSee($goldenBookPosts->get(0)->name);
        $response->assertSee($goldenBookPosts->get(0)->body);
        $response->assertDontSee($goldenBookPosts->get(0)->email);
        $response->assertSee($goldenBookPosts->get(1)->name);
        $response->assertSee($goldenBookPosts->get(1)->body);
        $response->assertDontSee($goldenBookPosts->get(1)->email);
    }

    public function test_guest_can_not_see_unactive_golden_book_index()
    {
        $goldenBookPosts = factory(GoldenBookPost::class, 2)->state('unactive')->create();

        $response = $this->showGoldenBooks();

        $response->assertStatus(200);
        $response->assertSee('Nothing to show');
        $response->assertDontSee($goldenBookPosts->get(0)->name);
        $response->assertDontSee($goldenBookPosts->get(0)->body);
        $response->assertDontSee($goldenBookPosts->get(0)->email);
        $response->assertDontSee($goldenBookPosts->get(1)->name);
        $response->assertDontSee($goldenBookPosts->get(1)->body);
        $response->assertDontSee($goldenBookPosts->get(1)->email);
    }
}
