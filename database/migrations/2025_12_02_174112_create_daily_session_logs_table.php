<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 3. `daily_session_logs` table (Patient's daily compliance and recovery metrics)
        Schema::create('daily_session_logs', function (Blueprint $table) {
            $table->id();
            
            // patient_id and protocol_id Foreign Keys
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('protocol_id')->constrained('protocols')->onDelete('cascade');

            $table->date('log_date');
            $table->tinyInteger('pain_score'); // 0-10
            $table->tinyInteger('difficulty_rating'); // 1-5
            $table->text('notes')->nullable();
            
            $table->json('completed_exercises')->nullable(); 
            
            $table->unique(['patient_id', 'protocol_id', 'log_date']); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_session_logs');
    }
};