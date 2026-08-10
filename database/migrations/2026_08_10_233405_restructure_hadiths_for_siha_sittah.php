<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The original `hadiths` table was a flat title/description/reference sheet holding two
 * placeholder rows. It cannot represent the Siha Sittah, which is six books, each with
 * numbered chapters, each hadith carrying Arabic, Bangla and English text plus a grading.
 *
 * The old table is dropped rather than migrated: its only contents were seeder
 * placeholders, reproduced faithfully by `php artisan hadith:import`.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hadith_books', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();      // bukhari, muslim, ...
            $table->string('name_en');
            $table->string('name_bn')->nullable();
            $table->string('name_ar')->nullable();
            $table->string('author')->nullable();
            $table->unsignedInteger('total_hadiths')->default(0);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('hadith_chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hadith_book_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('chapter_no');
            $table->string('name_en')->nullable();
            $table->string('name_bn')->nullable();
            $table->string('name_ar')->nullable();
            $table->unsignedInteger('hadith_first')->nullable();
            $table->unsignedInteger('hadith_last')->nullable();
            $table->unsignedInteger('total_hadiths')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->unique(['hadith_book_id', 'chapter_no']);
        });

        Schema::dropIfExists('hadiths');

        Schema::create('hadiths', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hadith_book_id')->constrained()->cascadeOnDelete();
            $table->foreignId('hadith_chapter_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('hadith_number');
            $table->unsignedInteger('arabic_number')->nullable();
            $table->text('arabic_text')->nullable();
            $table->text('bangla_text')->nullable();
            $table->text('english_text')->nullable();
            $table->string('grade')->nullable();
            $table->string('reference')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->unique(['hadith_book_id', 'hadith_number']);
            $table->index(['hadith_book_id', 'hadith_chapter_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hadiths');
        Schema::dropIfExists('hadith_chapters');
        Schema::dropIfExists('hadith_books');

        // Restore the original flat shape so the migration is reversible.
        Schema::create('hadiths', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('reference')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }
};
