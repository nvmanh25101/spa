<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Admin::factory(10)->create();

        // \App\Models\Schedule::factory()->create([
        //     'name' => 'Test Schedule',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            AdminSeeder::class,
            // CustomerSeeder::class,
        ]);
    }
}
