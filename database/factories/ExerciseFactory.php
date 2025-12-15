<?php

namespace Database\Factories;

use App\Models\Exercise;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exercise>
 */
class ExerciseFactory extends Factory
{
    protected $model = Exercise::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // CRITICAL FIX: Expanded the list to ensure there are enough unique names (10 available)
            // to satisfy the 8 total creations requested by the seeder.
            'name' => fake()->unique()->randomElement([
                'Knee Extension',
                'Hamstring Curl',
                'Calf Raises',
                'Shoulder Press',
                'Treadmill Walk',
                'Stair Climber',
                'Wall Squats',
                'Leg Press',
                'Bicep Curl',
                'Tricep Extension',
            ]),
            'instructions' => fake()->sentence(8),
        ];
    }
}