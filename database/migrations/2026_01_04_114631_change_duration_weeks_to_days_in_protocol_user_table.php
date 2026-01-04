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
        Schema::table('protocol_user', function (Blueprint $table) {
            $table->renameColumn('duration_weeks', 'duration_days');
        });
        
        // Update existing default if possible, or we just utilize the name change
        Schema::table('protocol_user', function (Blueprint $table) {
            $table->integer('duration_days')->default(30)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('protocol_user', function (Blueprint $table) {
            $table->renameColumn('duration_days', 'duration_weeks');
        });
    }
};
