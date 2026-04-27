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
        if (Schema::hasIndex('participants', ['calendar_of_training_id', 'employee_id'], 'unique')) {
            return;
        }

        Schema::table('participants', function (Blueprint $table) {
            $table->unique(['calendar_of_training_id', 'employee_id'], 'participants_training_employee_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasIndex('participants', 'participants_training_employee_unique', 'unique')) {
            return;
        }

        Schema::table('participants', function (Blueprint $table) {
            $table->dropUnique('participants_training_employee_unique');
        });
    }
};
