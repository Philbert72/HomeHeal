<?php

namespace Tests\Feature;

use App\Models\Protocol;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProtocolAuthorizationTest extends TestCase
{
    // Resets the database after each test
    use RefreshDatabase;

    /**
     * Set up common data for tests.
     */
    protected function setUp(): void
    {
        parent::setUp();
        // Create mock factories if they don't exist yet:
        // User::factory()->create(['role' => 'therapist']);
        // Protocol::factory()->create(['therapist_id' => $therapist->id]);
        // Since we are creating them inline, we skip this step.
    }

    /** @test */
    public function only_a_therapist_can_create_a_protocol()
    {
        // ARRANGE
        $therapist = User::factory()->create(['role' => 'therapist']);
        $patient = User::factory()->create(['role' => 'patient']);

        // ACT & ASSERT: Therapist should be ALLOWED to create
        $responseTherapist = $this->actingAs($therapist)
                                  ->post('/protocols', [
                                      'title' => 'New Protocol',
                                      'therapist_id' => $therapist->id
                                  ]);
        $responseTherapist->assertStatus(302); // Should redirect or assert success status

        // ACT & ASSERT: Patient should be DENIED creation access (403 Forbidden)
        $responsePatient = $this->actingAs($patient)
                                ->post('/protocols', [
                                    'title' => 'Should Fail',
                                    'therapist_id' => $patient->id
                                ]);
        $responsePatient->assertForbidden(); // Asserts 403 status
    }

    /** @test */
    public function a_therapist_can_view_their_own_protocol()
    {
        // ARRANGE
        $therapist = User::factory()->create(['role' => 'therapist']);
        $protocol = Protocol::factory()->create(['therapist_id' => $therapist->id]);

        // ACT & ASSERT
        $response = $this->actingAs($therapist)
                         ->get('/protocols/' . $protocol->id);

        $response->assertStatus(200); // Asserts successful access
    }

    /** @test */
    public function a_patient_is_denied_access_to_a_random_protocol()
    {
        // ARRANGE
        $therapist = User::factory()->create(['role' => 'therapist']);
        $patient = User::factory()->create(['role' => 'patient']);
        $protocol = Protocol::factory()->create(['therapist_id' => $therapist->id]);
        
        // NOTE: Since the ProtocolPolicy view method is simplified, this test is essential.
        // We assume the patient is NOT assigned to this protocol.

        // ACT & ASSERT
        $response = $this->actingAs($patient)
                         ->get('/protocols/' . $protocol->id);

        $response->assertForbidden(); // Asserts 403 Forbidden
    }

    /** @test */
    public function only_the_owning_therapist_can_update_a_protocol()
    {
        // ARRANGE
        $owner = User::factory()->create(['role' => 'therapist']);
        $intruder = User::factory()->create(['role' => 'therapist']);
        $protocol = Protocol::factory()->create(['therapist_id' => $owner->id, 'title' => 'Old Title']);

        // 1. Owner should be ALLOWED to update
        $responseOwner = $this->actingAs($owner)
                              ->put('/protocols/' . $protocol->id, ['title' => 'New Title', 'description' => 'Updated']);
        $responseOwner->assertStatus(302); // Asserts successful update/redirect

        // 2. Intruder Therapist should be DENIED update access
        $responseIntruder = $this->actingAs($intruder)
                                 ->put('/protocols/' . $protocol->id, ['title' => 'Attempted Hack']);
        $responseIntruder->assertForbidden();
    }
}