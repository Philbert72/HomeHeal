<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state (Patient).
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName() . ' ' . $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'), // default password: 'password'
            'role' => 'patient', // Default role for safety
            'locale' => $this->faker->randomElement(['en', 'es', 'fr']),
            'timezone' => $this->faker->timezone(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * State: User is a Therapist.
     */
    public function therapist(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Dr. ' . $this->faker->lastName(),
            'role' => 'therapist',
        ]);
    }

    /**
     * State: User is explicitly a Patient.
     */
    public function patient(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'patient',
        ]);
    }
}