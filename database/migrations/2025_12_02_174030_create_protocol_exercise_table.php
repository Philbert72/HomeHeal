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
        Schema::create('protocol_exercise', function (Blueprint $table) {
            
            $table->foreignId('protocol_id')->constrained('protocols')->onDelete('cascade');
            $table->foreignId('exercise_id')->constrained('exercises')->onDelete('cascade');
            $table->primary(['protocol_id', 'exercise_id']);
            $table->tinyInteger('sets'); 
            $table->tinyInteger('reps'); 
            $table->decimal('resistance_amount', 8, 2)->nullable(); 
            $table->smallInteger('rest_seconds'); 
            $table->string('resistance_original_unit', 10)->default('g'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('protocol_exercise');
    }
};