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
        Schema::table('employees', function (Blueprint $table) {
            $table->string('full_name', 135)
                ->virtualAs('CONCAT(first_name, " ", IF(middle_name IS NULL, "", concat( LEFT(middle_name, 1), ". ")), last_name, " ")')
                ->after('last_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('full_name');
        });
    }
};
