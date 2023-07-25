<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Travel;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminTravelTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_public_user_cannot_access_adding_travel(): void
    {
        $response = $this->postJson('/api/v1/admin/travels');
        $response->assertStatus(401);
    }

    public function test_non_admin_user_cannot_add_travel() : void
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();

        /**
         * attaching roles to that user
         * actingAs mean setting loggedin user for the application
         */
        $user->roles()->attach(Role::where('name', 'editor')->value('id'));

        // actingAs mean setting loggedin user for the application
        $response = $this->actingAs($user)->postJson('/api/v1/admin/travels');

        $response->assertStatus(403);
    }

    public function test_saves_travel_successfully_with_valid_data() :void
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();

        $user->roles()->attach(Role::where('name', 'admin')->value('id'));
        $response = $this->actingAs($user)->postJson('/api/v1/admin/travels', [
            'name' => 'First Travel',
        ]);
        $response->assertStatus(422);
        // validation fires and return errors because we just enter the name only, not all of the required fields

        $response = $this->actingAs($user)->postJson('/api/v1/admin/travels',[
            'name' => 'Travel name',
            'is_public' => 1,
            'description' => 'This is the description',
            'number_of_days' => 3
        ]);
        $response->assertStatus(201);
        $response = $this->get('/api/v1/travels');
        $response->assertJsonFragment(['name' => 'Travel name']);

    }

    public function test_update_travels_successfully_with_validate_data() :void
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'admin')->value('id'));

        $travel = Travel::factory()->create();
        $response = $this->actingAs($user)->putJson('api/v1/admin/travels/'.$travel->id, [
            'name' => 'Updated Travel by Test',
        ]);

        $response->assertStatus(422);

        $response = $this->actingAs($user)->putJson('api/v1/admin/travels/'.$travel->id,[
            'name' => 'Updated Travel',
            'is_public' => 1,
            'description' => 'Description is also update',
            'number_of_days' => 4
        ]);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/travels');
        $response->assertJsonFragment(['name' => 'Updated Travel']);

    }
}
