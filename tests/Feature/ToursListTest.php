<?php

namespace Tests\Feature;

use App\Models\Tour;
use Tests\TestCase;
use App\Models\Travel;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ToursListTest extends TestCase
{
    use RefreshDatabase;
    /**
     * we will have 4 types of test
     * 1)Pagination
     * 2)To check if the price is returned correct.
     * 3)To fetch record by slug
     */
    public function test_tours_list_by_travel_slug_returns_correct_tours(): void
    {
        $travel = Travel::factory()->create();
        $tour = Tour::factory()->create(['travel_id' =>  $travel->id]);

        $response = $this->get('/api/v1/travels/' . $travel->slug . '/tours');
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['id' => $tour->id]);
    }

    public function test_tour_price_is_shown_correctly()
    {
        $travel = Travel::factory()->create();
        $tour = Tour::factory()->create(['travel_id' => $travel->id, 'price' => 123.45]);

        $response = $this->get('api/v1/travels/' . $travel->slug . '/tours');
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['price' => '123.45']);
    }

    public function test_tours_list_return_paingation(){
        $travel = Travel::factory()->create();
        $tour = Tour::factory(16)->create(['travel_id' => $travel->id]);

        $response = $this->get('api/v1/travels/'.$travel->slug.'/tours');
        $response->assertStatus(200);
        $response->assertJsonCount(15, 'data');
        $response->assertJsonPath('meta.last_page', 2);
    }
}
