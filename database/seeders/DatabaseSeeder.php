<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user only - no fake data
        User::factory()->create([
            'name' => 'مدير النظام',
            'email' => 'admin@mutmainah.com',
            'password' => bcrypt('password'),
        ]);
    }
}
