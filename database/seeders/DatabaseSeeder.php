<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            CitiesSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            SizeSeeder::class,
            LanguageSeeder::class,
            CityTranslationSeeder::class,
            ColorTranslationSeeder::class,
            CategoryTranslationSeeder::class,
            ProductTranslationSeeder::class,
            PaymentStatusSeeder::class,
        ]);
    }
}
