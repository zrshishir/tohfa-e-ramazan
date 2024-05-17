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
        Schema::create('suras', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('arabic_name');
            $table->string('english_name');
            $table->string('bangla_text');
            $table->string('meaning');
            $table->string('ayat_count')->nullable();
            $table->string('type')->nullable();
            $table->string('revelation_order')->nullable();
            $table->string('rukus')->nullable();
            $table->string('place_of_revelation')->nullable();
            $table->string('status');
            $table->string('audio')->nullable();
            $table->string('video')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suras');
    }
};
