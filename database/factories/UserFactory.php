<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     * All new users will be 'patient' by default.
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            // CRITICAL FIXES based on user table schema:
            // 1. 'email_verified_at' field removed.
            // 2. 'remember_token' field removed as it does not exist in the database schema.
            'password' => Hash::make('password'), 
            
            // Default role is 'patient' unless otherwise specified
            'role' => 'patient', 
            'locale' => 'en',
            'timezone' => 'UTC', // Using the factory default, but your DB defaults to 'WIB'
        ];
    }
    
    public function therapist(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'therapist',
        ]);
    }
    
    public function patient(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'patient',
        ]);
    }
    
    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
        ]);
    }
}