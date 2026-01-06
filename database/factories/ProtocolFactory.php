<?php

namespace Database\Factories;

use App\Models\Protocol;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProtocolFactory extends Factory
{
    protected $model = Protocol::class;

    public function definition(): array
    {
        return [
            'therapist_id' => User::factory()->therapist(),
            'title' => $this->faker->words(3, true) . ' Rehab Protocol',
            'description' => $this->faker->sentence(),
        ];
    }
}