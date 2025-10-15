<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Category::factory()->create([
            "name" => 'Grocery'
        ]);
        Category::factory()->create([
            "name" => 'Fruits'
        ]);
        Category::factory()->create([
            "name" => 'Meat'
        ]);
        Category::factory()->create([
            "name" => 'Transport'
        ]);
        Category::factory()->create([
            "name" => 'Rent'
        ]);
        Category::factory()->create([
            "name" => 'Utilities',
        ]);


        User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);
        User::factory()->create([
            'name' => 'Test Manager',
            'email' => 'manager@example.com',
            'role' => 'manager',
        ]);

    }
}
