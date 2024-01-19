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
        Schema::create('ayats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sura_id')->constrained();
            $table->integer('ayat_no');
            $table->string('arabic_text');
            $table->string('bangla_text');
            $table->string('english_text');
            $table->string('meaning');
            $table->string('reference');
            $table->string('notes');
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
        Schema::dropIfExists('ayats');
    }
};
