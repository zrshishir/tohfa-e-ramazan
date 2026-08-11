<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Server-side bookmarks, so a reading list survives a reinstall and follows a user
 * between devices. Until now bookmarks lived only in the app's localStorage.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookmarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ayat_id')->constrained()->cascadeOnDelete();
            // Denormalised so the list screen needs no join to render.
            $table->foreignId('sura_id')->nullable()->constrained('suras')->nullOnDelete();
            $table->unsignedInteger('ayat_no')->nullable();
            $table->unsignedInteger('page')->nullable();
            $table->timestamps();

            // One bookmark per ayat per user; re-bookmarking is idempotent.
            $table->unique(['user_id', 'ayat_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookmarks');
    }
};
