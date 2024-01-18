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
            $table->json('sehri')->nullable();
            $table->json('fazr')->nullable();
            $table->json('sunrise')->nullable();
            $table->json('ishraq')->nullable();
            $table->json('johr')->nullable();
            $table->json('asr')->nullable();
            $table->json('magrib')->nullable();
            $table->json('esha')->nullable();
            $table->json('tahazzud')->nullable();
            $table->json('jummah')->nullable();
            $table->json('forbidden')->nullable();
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