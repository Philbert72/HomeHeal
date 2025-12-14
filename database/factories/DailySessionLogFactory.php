<?php

namespace Database\Factories;

use App\Models\DailySessionLog;
use App\Models\User;
use App\Models\Protocol;
use Illuminate\Database\Eloquent\Factories\Factory;

class DailySessionLogFactory extends Factory
{
    protected $model = DailySessionLog::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        // 1. Create a Patient (User with role='patient')
        // We use the 'patient' state from the UserFactory
        $patient = User::factory()->patient()->create();
        
        // 2. Create a Protocol owned by the Patient's Therapist.
        // For simplicity, we create a new Therapist and a new Protocol for every log.
        $therapist = User::factory()->therapist()->create();
        $protocol = Protocol::factory()->create(['therapist_id' => $therapist->id]);

        return [
            'patient_id' => $patient->id,
            'protocol_id' => $protocol->id,
            'log_date' => $this->faker->dateTimeBetween('-1 week', 'now')->format('Y-m-d'),
            
            // Metrics data
            'pain_score' => $this->faker->numberBetween(1, 10),
            'difficulty_rating' => $this->faker->numberBetween(1, 5),
            'notes' => $this->faker->paragraph(1),
        ];
    }
}