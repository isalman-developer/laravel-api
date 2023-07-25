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
     * 4)To fetch record by starting date (latest record first)
     * 5)To check whether the validation fails and return status 422 or not if it returns then test is accurate
     */
    public function test_tours_list_return_paingation(): void
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
        Tour::factory()->create(['travel_id' => $travel->id, 'price' => 123.45]);

        $response = $this->get('api/v1/travels/' . $travel->slug . '/tours');
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['price' => '123.45']);
    }

    public function test_tours_list_by_travel_slug_returns_correct_tours()
    {
        $travel = Travel::factory()->create();
        Tour::factory(16)->create(['travel_id' => $travel->id]);

        $response = $this->get('api/v1/travels/' . $travel->slug . '/tours');
        $response->assertStatus(200);
        $response->assertJsonCount(15, 'data');
        $response->assertJsonPath('meta.last_page', 2);
    }

    public function test_tours_list_return_by_starting_date_correctly(): void
    {
        $travel = Travel::factory()->create();
        $earlierTour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'starting_date' => now(),
            'ending_date' => now()->addDays(1)
        ]);

        $lateTour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'starting_date' => now()->addDays(2),
            'ending_date' => now()->addDays(3),
        ]);

        $response = $this->get("api/v1/travels/{$travel->slug}/tours");
        $response->assertStatus(200);
        $response->assertJsonPath('data.0.id', $earlierTour->id);
        $response->assertJsonPath('data.1.id', $lateTour->id);
    }

    public function test_tours_list_sorts_by_price_correctly(): void
    {
        $travel = Travel::factory()->create();
        $expensiveTour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 200,
        ]);
        $cheapLaterTour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 100,
            'starting_date' => now()->addDays(2),
            'ending_date' => now()->addDays(3),
        ]);
        $cheapEarlierTour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 100,
            'starting_date' => now(),
            'ending_date' => now()->addDays(1),
        ]);

        $response = $this->get('/api/v1/travels/' . $travel->slug . '/tours?sortBy=price&sortOrder=asc');

        $response->assertStatus(200);
        $response->assertJsonPath('data.0.id', $cheapEarlierTour->id);
        $response->assertJsonPath('data.1.id', $cheapLaterTour->id);
        $response->assertJsonPath('data.2.id', $expensiveTour->id);
    }

    public function test_tour_list_returns_validation_errors(): void
    {
        $travel = Travel::factory()->create();

        $response = $this->getJson('/api/v1/travels/' . $travel->slug . '/tours?dateFrom=abcde');
        $response->assertStatus(422);//expected status should be 422 mean there is validation error

        $response = $this->getJson('/api/v1/travels/' . $travel->slug . '/tours?priceFrom=abcde');
        $response->assertStatus(422);//expected status should be 422 mean there is validation error

    }
}
