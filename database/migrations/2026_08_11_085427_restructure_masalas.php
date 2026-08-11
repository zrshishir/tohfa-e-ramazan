<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Masa-el were a flat `title` / `description` list with no grouping, so the screen could
 * only ever be one long scroll. Fiqh questions are browsed by topic — purification,
 * prayer, fasting — so this adds categories, and renames the columns to what they
 * actually hold: a question and its answer.
 *
 * The two existing placeholder rows are preserved and moved into a category.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('masala_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_bn')->nullable();
            $table->string('name_ar')->nullable();
            $table->string('slug')->unique();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::table('masalas', function (Blueprint $table) {
            $table->foreignId('masala_category_id')->nullable()->after('id')
                ->constrained()->nullOnDelete();
            $table->unsignedSmallInteger('sort_order')->default(0)->after('reference');
        });

        // `title` held the question and `description` the answer; name them so.
        // One rename per statement: SQLite rejects multiple renameColumn calls in a
        // single modification, and the test suite runs on SQLite.
        Schema::table('masalas', function (Blueprint $table) {
            $table->renameColumn('title', 'question');
        });

        Schema::table('masalas', function (Blueprint $table) {
            $table->renameColumn('description', 'answer');
        });
    }

    public function down(): void
    {
        Schema::table('masalas', function (Blueprint $table) {
            $table->renameColumn('question', 'title');
        });

        Schema::table('masalas', function (Blueprint $table) {
            $table->renameColumn('answer', 'description');
        });

        Schema::table('masalas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('masala_category_id');
            $table->dropColumn('sort_order');
        });

        Schema::dropIfExists('masala_categories');
    }
};
