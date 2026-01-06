<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Exercise;
use App\Models\Protocol;
use App\Models\DailySessionLog;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Users
        $therapist = User::factory()->therapist()->create([
            'name' => 'Dr. Therapist',
            'email' => 'therapist@homeheal.com',
            'password' => Hash::make('password'),
        ]);
        $patient = User::factory()->patient()->create([
            'name' => 'Jane Patient',
            'email' => 'patient@homeheal.com',
            'password' => Hash::make('password'),
        ]);
        $otherPatients = User::factory(5)->patient()->create();


        // Create Exercises 
        $exercises = [
            Exercise::factory()->create(['name' => 'Quad Set', 'instructions' => 'Tighten your thigh muscles and push the back of your knee down into the floor. Hold for 5 seconds and release. This exercise helps strengthen the quadriceps muscle.']),
            Exercise::factory()->create(['name' => 'Straight Leg Raise', 'instructions' => 'Lie on your back with one leg bent. Keep the other leg straight and lift it 6-12 inches off the ground. Hold for 3 seconds, then slowly lower. Repeat 10-15 times.']),
            Exercise::factory()->create(['name' => 'Resistance Band Rows', 'instructions' => 'Loop a resistance band around a sturdy object at chest height. Pull the ends toward your chest, squeezing your shoulder blades together. Keep your back straight throughout the movement.']),
            Exercise::factory()->create(['name' => 'Heel Slides', 'instructions' => 'Lying on your back, slowly slide your heel toward your buttocks, bending your knee. Use a towel under your heel if needed. Hold for 2 seconds, then slide back to starting position.']),
            Exercise::factory()->create(['name' => 'Ankle Pumps', 'instructions' => 'While lying down or sitting, move your foot up and down at the ankle. Perform 10-20 repetitions, 3-4 times per day to improve circulation.']),
            Exercise::factory()->create(['name' => 'Hamstring Curls', 'instructions' => 'Stand holding onto a chair for balance. Slowly bend one knee, bringing your heel toward your buttocks. Lower slowly and repeat 10-15 times on each leg.']),
            Exercise::factory()->create(['name' => 'Wall Squats', 'instructions' => 'Stand with your back against a wall. Slide down into a squat position (thighs parallel to floor). Hold for 5-10 seconds, then slide back up. Start with 5 reps and gradually increase.']),
            Exercise::factory()->create(['name' => 'Calf Raises', 'instructions' => 'Stand with feet hip-width apart. Rise up onto your toes, hold for 2 seconds, then lower slowly. Perform 15-20 repetitions. Use a wall or chair for balance if needed.']),
        ];


        // Create Protocols
        $protocol1 = Protocol::create([
            'therapist_id' => $therapist->id,
            'title' => 'Knee Rehabilitation - Post Surgery Phase 1',
            'description' => 'Initial recovery protocol for patients recovering from knee surgery. Focus on reducing swelling, regaining range of motion, and beginning gentle strengthening.',
        ]);
        $protocol1->exercises()->attach([
            $exercises[0]->id => ['sets' => 3, 'reps' => 10, 'resistance_amount' => 0, 'rest_seconds' => 30, 'resistance_original_unit' => 'g'],
            $exercises[1]->id => ['sets' => 2, 'reps' => 10, 'resistance_amount' => 0, 'rest_seconds' => 45, 'resistance_original_unit' => 'g'],
            $exercises[3]->id => ['sets' => 3, 'reps' => 15, 'resistance_amount' => 0, 'rest_seconds' => 20, 'resistance_original_unit' => 'g'],
            $exercises[4]->id => ['sets' => 5, 'reps' => 20, 'resistance_amount' => 0, 'rest_seconds' => 15, 'resistance_original_unit' => 'g'],
        ]);
        $protocol2 = Protocol::create([
            'therapist_id' => $therapist->id,
            'title' => 'Shoulder Strengthening & Mobility',
            'description' => 'Progressive shoulder rehabilitation program focused on restoring strength and range of motion after rotator cuff injury.',
        ]);
        $protocol2->exercises()->attach([
            $exercises[2]->id => ['sets' => 3, 'reps' => 12, 'resistance_amount' => 2268, 'rest_seconds' => 60, 'resistance_original_unit' => 'kg'], // 2.268 kg = 5 lbs band
        ]);
        $protocol3 = Protocol::create([
            'therapist_id' => $therapist->id,
            'title' => 'Lower Body Strength & Conditioning',
            'description' => 'Comprehensive lower body strengthening program for patients building back strength and stability.',
        ]);

        $protocol3->exercises()->attach([
            $exercises[5]->id => ['sets' => 3, 'reps' => 12, 'resistance_amount' => 0, 'rest_seconds' => 45, 'resistance_original_unit' => 'g'],
            $exercises[6]->id => ['sets' => 3, 'reps' => 10, 'resistance_amount' => 0, 'rest_seconds' => 60, 'resistance_original_unit' => 'g'],
            $exercises[7]->id => ['sets' => 3, 'reps' => 15, 'resistance_amount' => 0, 'rest_seconds' => 30, 'resistance_original_unit' => 'g'],
        ]);


        // Assign Protocols to Patients
        $protocol1->patients()->attach($patient->id);
        $protocol3->patients()->attach($patient->id);

        foreach ($otherPatients as $index => $otherPatient) {
            if ($index % 2 == 0) {
                $protocol1->patients()->attach($otherPatient->id);
            } else {
                $protocol2->patients()->attach($otherPatient->id);
            }
        }


        // Create Session Logs
        for ($daysAgo = 29; $daysAgo >= 0; $daysAgo--) {
            if ($daysAgo % 4 == 0 || $daysAgo % 7 == 6) {
                continue; 
            }

            $logDate = now()->subDays($daysAgo);

            if ($daysAgo > 20) {
                $painScore = rand(6, 8);
                $difficulty = rand(4, 5);
            } elseif ($daysAgo > 10) {
                $painScore = rand(4, 6);
                $difficulty = rand(3, 4);
            } else {
                $painScore = rand(2, 4);
                $difficulty = rand(2, 3);
            }

            $protocolId = ($daysAgo % 2 == 0) ? $protocol1->id : $protocol3->id;

            $notes = [
                'Feeling good today, completed all exercises.',
                'Some discomfort but manageable.',
                'Really seeing improvement!',
                'Exercises getting easier.',
                'Had to take it slow today.',
                'Great session, feeling stronger.',
                'Slight pain but pushed through.',
                'Best session yet!',
                null,
                null,
            ];

            DailySessionLog::create([
                'patient_id' => $patient->id,
                'protocol_id' => $protocolId,
                'log_date' => $logDate->format('Y-m-d'),
                'pain_score' => $painScore,
                'difficulty_rating' => $difficulty,
                'notes' => $notes[array_rand($notes)],
            ]);
        }
    }
}