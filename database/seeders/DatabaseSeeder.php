<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $travel = Travel::factory(1)->create(['name' => 'First Travel']);
        Tour::factory(2)->create(['travel_id' =>  $travel[0]->id]);

        $travel = Travel::factory(1)->create(['name' => 'Second Travel']);
        Tour::factory(2)->create(['travel_id' =>  $travel[0]->id]);

        $this->call(RoleSeeder::class);
    }
}
