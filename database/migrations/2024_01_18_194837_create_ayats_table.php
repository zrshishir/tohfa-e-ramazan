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
            $table->text('arabic_text');
            $table->text('bangla_text');
            $table->text('english_text');
            $table->text('meaning');
            $table->string('reference');
            $table->text('notes');
            $table->string('status');
            $table->text('audio')->nullable();
            $table->text('video')->nullable();
            $table->text('image')->nullable();
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