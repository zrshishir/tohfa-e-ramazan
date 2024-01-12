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
        Schema::create('mazhab_wise_schedule_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mazhab_id')->constrained('mazhabs');
            $table->integer('sehri_time')->default(0);
            $table->integer('fazr_time')->default(0);
            $table->integer('ishraq_time')->default(0);
            $table->integer('johr_time')->default(0);
            $table->integer('asr_time')->default(0);
            $table->integer('magrib_time')->default(0);
            $table->integer('iftar_time')->default(0);
            $table->integer('esha_time')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mazhab_wise_schedule_settings');
    }
};