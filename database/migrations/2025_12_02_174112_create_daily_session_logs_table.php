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
        Schema::create('daily_session_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('protocol_id')->constrained('protocols')->onDelete('cascade');

            $table->date('log_date');
            $table->smallInteger('pain_score');
            $table->smallInteger('difficulty_rating');
            $table->text('notes')->nullable();
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
