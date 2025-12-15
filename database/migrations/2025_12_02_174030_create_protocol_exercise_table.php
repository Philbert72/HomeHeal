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
        // CRITICAL FIX: Changed table name from 'protocol_exercises' (plural) to 'protocol_exercise' (singular)
        Schema::create('protocol_exercise', function (Blueprint $table) {
            
            // Primary Composite Key
            $table->foreignId('protocol_id')->constrained('protocols')->onDelete('cascade');
            $table->foreignId('exercise_id')->constrained('exercises')->onDelete('cascade');
            $table->primary(['protocol_id', 'exercise_id']);

            // Prescription Details (Matching the ProtocolController and Model)
            // Using tinyInteger to match the established small data type for sets/reps
            $table->tinyInteger('sets'); 
            $table->tinyInteger('reps'); 
            
            $table->decimal('resistance_amount', 8, 2)->nullable(); 
            
            // CRITICAL FIX: Changed 'rest_amount' to 'rest_seconds' to match the Controller/Model logic
            $table->smallInteger('rest_seconds'); 
            
            // The column was missing, but is needed for conversion logic
            $table->string('resistance_original_unit', 10)->default('g'); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // CRITICAL FIX: Changed table name to singular form
        Schema::dropIfExists('protocol_exercise');
    }
};