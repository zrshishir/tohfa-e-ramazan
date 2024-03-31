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
        Schema::create('district_wise_schedule_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('district_id')->references('id')->on('districts')->onDelete('cascade');
            $table->integer('sehri_time');
            $table->integer('iftar_time');
            $table->boolean('is_active')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('district_wise_schedule_settings');
    }
};
