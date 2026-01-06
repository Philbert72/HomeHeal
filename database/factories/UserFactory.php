<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'), 
            'role' => 'patient', 
            'locale' => 'en',
            'timezone' => 'UTC',
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
    
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
        ]);
    }
}