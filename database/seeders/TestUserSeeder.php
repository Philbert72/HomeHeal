<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Therapist user with known credentials
        User::factory()->therapist()->create([
            'name' => 'Dr. Therapist',
            'email' => 'therapist@homeheal.com',
            'password' => Hash::make('password'), // Password is 'password'
        ]);

        // Patient user with known credentials
        User::factory()->create([
            'name' => 'John Patient',
            'email' => 'patient@homeheal.com',
            'password' => Hash::make('password'), // Password is 'password'
        ]);

        // Create 5 random patients
        User::factory(5)->create();
    }
}