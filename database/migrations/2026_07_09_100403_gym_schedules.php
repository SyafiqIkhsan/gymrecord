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
        Schema::create('gym_schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('target_per_week')->default(3);
            $table->json('target_days'); // Menyimpan array hari, misal: ["Senin", "Rabu", "Jumat"]
            $table->time('reminder_time')->nullable(); // Jam untuk pengingat
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
