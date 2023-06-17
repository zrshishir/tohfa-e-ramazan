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
        Schema::create('doas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('doa_category_id')->nullable()->constrained('doa_categories')->onDelete('cascade');
            $table->string('title');
            $table->string('doa_for');
            $table->text('description')->nullable();
            $table->text('arabic_text')->nullable();
            $table->text('bangla_text')->nullable();
            $table->text('english_text')->nullable();
            $table->text('meaning')->nullable();
            $table->string('reference')->nullable();
            $table->text('when_to_use')->nullable();
            $table->text('notes')->nullable();
            $table->text('when_not_to_use')->nullable();
            $table->boolean('status')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doas');
    }
};
