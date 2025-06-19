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
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create(
            [
                'name' => 'Huda',
                'email' => 'fairuzalimah.uh@gmail.com',
                'password' => bcrypt('admin123'),
            ]
        );
        User::factory()->create(
            [
                'name' => 'Fay',
                'email' => 'fairuzalimahulayyahuda@mail.ugm.ac.id',
                'password' => bcrypt('admin123'),
            ]
        );
        User::factory()->create(
            [
                'name' => 'ima',
                'email' => 'samneoguri@gmail.com',
                'password' => bcrypt('admin123'),
            ]
        );
    }
}
