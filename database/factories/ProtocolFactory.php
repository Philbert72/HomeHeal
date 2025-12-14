<?php

namespace Database\Factories;

use App\Models\Protocol;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProtocolFactory extends Factory
{
    protected $model = Protocol::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        // Ensure the therapist_id links to an existing user with the 'therapist' role.
        // If the user doesn't exist, create one using the 'therapist' state from UserFactory.
        return [
            'therapist_id' => User::factory()->therapist(),
            'title' => $this->faker->words(3, true) . ' Rehab Protocol',
            'description' => $this->faker->sentence(),
        ];
    }
}