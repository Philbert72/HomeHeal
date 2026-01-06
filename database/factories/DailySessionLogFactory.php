<?php

namespace Database\Factories;

use App\Models\DailySessionLog;
use App\Models\User;
use App\Models\Protocol;
use Illuminate\Database\Eloquent\Factories\Factory;

class DailySessionLogFactory extends Factory
{
    protected $model = DailySessionLog::class;

    public function definition(): array
    {
        $patient = User::factory()->patient()->create();
        $therapist = User::factory()->therapist()->create();
        $protocol = Protocol::factory()->create(['therapist_id' => $therapist->id]);

        return [
            'patient_id' => $patient->id,
            'protocol_id' => $protocol->id,
            'log_date' => $this->faker->dateTimeBetween('-1 week', 'now')->format('Y-m-d'),
            'pain_score' => $this->faker->numberBetween(1, 10),
            'difficulty_rating' => $this->faker->numberBetween(1, 5),
            'notes' => $this->faker->paragraph(1),
        ];
    }
}