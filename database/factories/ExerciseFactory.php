<?php

namespace Database\Factories;

use App\Models\Exercise;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExerciseFactory extends Factory
{
    protected $model = Exercise::class;

    public function definition(): array
    {
        return [
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