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
        Schema::create('asmaul_husnas', function (Blueprint $table) {
            $table->id();
            $table->string('arabic_name');
            $table->string('bangla_name');
            $table->string('english_name');
            $table->string('meaning')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asmaul_husnas');
    }
};
