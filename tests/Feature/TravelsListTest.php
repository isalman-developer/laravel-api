<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Travel;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TravelsListTest extends TestCase
{
    use RefreshDatabase;

    public function test_travel_list_returns_paginated_data_correctly()
    {
        Travel::factory(16)->create(['is_public' => true]);
        $response = $this->get('/api/v1/travels');

        $response->assertStatus(200);
        // this checks whether the data object in response have 15 records because by default laravel fetch 15 records, and we are going to insert 16 records using factory.
        $response->assertJsonCount(15, 'data');

        // if there are 16 records then met will have 2 pages, each page have 15 records be default. and the last page will be 2 as we are inserting 16 records so 1st page = 15, 2nd page = 1
        $response->assertJsonPath('meta.last_page', 2);
    }

    public function test_travel_list_shows_only_public()
    {
        $record = Travel::factory()->create(['is_public' => true]);
        Travel::factory(1)->create(['is_public' => false]);

        $response = $this->get('/api/v1/travels');
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.name', $record->name);
    }
}
