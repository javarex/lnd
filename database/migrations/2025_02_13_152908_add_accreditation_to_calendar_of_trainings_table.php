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
            $table->string('accreditation_number')->after('end_date')->nullable();
            $table->string('approved_credit_units')->after('accreditation_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calendar_of_trainings', function (Blueprint $table) {
            $table->dropColumn('accreditation_number');
            $table->dropColumn('approved_credit_units');
        });
    }
};
