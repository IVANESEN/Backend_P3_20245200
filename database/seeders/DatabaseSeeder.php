<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    /**public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    } */
    public function run(): void
    {
        $csvFile = database_path('data/libros.csv');
        if (file_exists($csvFile)) {
            $handle = fopen($csvFile, 'r');
            fgetcsv($handle);
            while (($data = fgetcsv($handle)) !== FALSE) {
                \App\Models\Book::create([
                    'title' => $data[0],
                    'description' => $data[1],
                    'isbn' => $data[2],
                    'total_copies' => (int)$data[3],
                    'available_copies' => (int)$data[4],
                    'status' => strtolower($data[5]) === 'disponible',
                ]);
            }
            fclose($handle);
        }
   }
}
