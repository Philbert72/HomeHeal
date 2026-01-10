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
        Schema::table('daily_session_logs', function (Blueprint $table) {
            $table->unique(['patient_id', 'protocol_id', 'log_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_session_logs', function (Blueprint $table) {
            $table->dropUnique(['patient_id', 'protocol_id', 'log_date']);
        });
    }
};
