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
        Schema::create('tasbih', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate();
            $table->json('subhanallah')->nullable();
            $table->json('alhamdulillah')->nullable();
            $table->json('allahuakbar')->nullable();
            $table->json('astagfirullah')->nullable();
            $table->json('la_ilaha_illallah')->nullable();
            $table->json('subhanallahi_wabi_hamdihi_wa_subhanallahil_azeem')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasbih');
    }
};