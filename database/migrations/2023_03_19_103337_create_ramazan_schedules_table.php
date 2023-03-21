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
        Schema::create('ramazan_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('roza_no');
            $table->string('title');
            $table->string('shehri_time');
            $table->string('iftar_time');
            $table->string('day');
            $table->timestamp('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ramazan_schedules');
    }
};
