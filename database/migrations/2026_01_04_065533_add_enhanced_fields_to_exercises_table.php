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
        Schema::table('exercises', function (Blueprint $table) {
            $table->text('equipment_needed')->nullable()->after('image_path');
            $table->text('safety_warnings')->nullable()->after('equipment_needed');
            $table->text('step_by_step_guide')->nullable()->after('safety_warnings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exercises', function (Blueprint $table) {
            $table->dropColumn(['equipment_needed', 'safety_warnings', 'step_by_step_guide']);
        });
    }
};
