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
        Schema::table('calendar_of_trainings', function (Blueprint $table) {
            $table->enum('status', ['Approved', 'Disapproved', 'Pending'])->default('Pending')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calendar_of_trainings', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
