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
       Schema::create('permanent_calendars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('month_id')
                    ->constrained()
                    ->cascadeOnUpdate();
            $table->string('day')->nullable();
            $table->string('sehri_time')->nullable();
            $table->string('fazr_time')->nullable();
            $table->string('sunrise_time')->nullable();
            $table->string('ishraq_time')->nullable();
            $table->string('johr_time')->nullable();
            $table->string('asr_time')->nullable();
            $table->string('magrib_and_iftar_time')->nullable();
            $table->string('esha_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permanent_calendars');
    }
};
