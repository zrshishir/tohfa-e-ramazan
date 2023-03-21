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
            $table->unsignedBigInteger('district_id');
            $table->integer('time_addition_subtraction');
            $table->integer('am_pm');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('district_wise_schedule_settings', function (Blueprint $table) {
            //
        });
    }
};
