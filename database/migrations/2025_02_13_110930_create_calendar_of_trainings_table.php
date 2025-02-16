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
        Schema::create('calendar_of_trainings', function (Blueprint $table) {
            $table->id();
            $table->integer('training_id')->length(10);
            $table->integer('venue_id')->length(10);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->integer('user_id')->length(10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_of_trainings');
    }
};
