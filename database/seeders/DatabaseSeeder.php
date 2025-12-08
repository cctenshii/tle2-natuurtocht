<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Roep alle seeders aan die je wilt uitvoeren
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
        ]);
    }
}
