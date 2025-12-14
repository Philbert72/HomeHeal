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
        // This pivot table links a Patient (user_id) to a Protocol (protocol_id) for assignment tracking.
        Schema::create('protocol_user', function (Blueprint $table) {
            
            // Foreign key to the protocols table
            $table->foreignId('protocol_id')->constrained('protocols')->onDelete('cascade');
            
            // Foreign key to the users table (the Patient/Assigned User)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Ensures a patient is only assigned to a protocol once (Composite Primary Key)
            $table->primary(['protocol_id', 'user_id']);
            
            // Optional: Add a timestamp for when the assignment was made
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('protocol_user');
    }
};