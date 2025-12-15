<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Exercise; // CRITICAL IMPORT
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // --- Create Users ---
        
        // Therapist user with known credentials
        User::factory()->therapist()->create([
            'name' => 'Dr. Therapist',
            'email' => 'therapist@homeheal.com',
            'password' => Hash::make('password'), // Password is 'password'
        ]);

        // Regular Patient user with known credentials (optional, but useful)
        User::factory()->patient()->create([
            'name' => 'Jane Patient',
            'email' => 'patient@homeheal.com',
            'password' => Hash::make('password'), // Password is 'password'
        ]);

        // 5 random patients
        User::factory(5)->patient()->create();


        // --- Create Exercises (Dummy Data for Protocol Creation) ---
        
        // Create specific, non-random exercises for easy testing
        Exercise::factory()->create(['name' => 'Quad Set', 'instructions' => 'Tighten your thigh muscles and push the back of your knee down into the floor. Hold for 5 seconds.']);
        Exercise::factory()->create(['name' => 'Straight Leg Raise', 'instructions' => 'Lie on your back, keep one leg straight and lift it 6 inches off the ground.']);
        Exercise::factory()->create(['name' => 'Resistance Band Rows', 'instructions' => 'Loop a band around your foot and pull the ends toward your chest, squeezing your shoulder blades.']);
        Exercise::factory()->create(['name' => 'Treadmill Walk', 'instructions' => 'Walk at a moderate pace.']);
        Exercise::factory()->create(['name' => 'Cycling (Stationary)', 'instructions' => 'Maintain an easy, steady pace.']);
        
        // Create 3 more random exercises
        Exercise::factory(3)->create(); 
    }
}