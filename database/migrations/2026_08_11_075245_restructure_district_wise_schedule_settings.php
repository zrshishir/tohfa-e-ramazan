<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The table held a single `time_addition_subtraction` value per district, but the
 * Islamic Foundation's district table gives **separate** sehri and iftar offsets —
 * Cox's Bazar is −1 for sehri and −10 for iftar, Rangpur is −1 and +8. One column
 * cannot represent that.
 *
 * The table was empty (0 rows), so nothing is migrated across.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('district_wise_schedule_settings', function (Blueprint $table) {
            $table->dropColumn(['time_addition_subtraction', 'am_pm']);
        });

        Schema::table('district_wise_schedule_settings', function (Blueprint $table) {
            // Minutes relative to Dhaka. Positive adds, negative subtracts.
            $table->integer('sehri_offset')->default(0)->after('district_id');
            $table->integer('iftar_offset')->default(0)->after('sehri_offset');
        });
    }

    public function down(): void
    {
        Schema::table('district_wise_schedule_settings', function (Blueprint $table) {
            $table->dropColumn(['sehri_offset', 'iftar_offset']);
        });

        Schema::table('district_wise_schedule_settings', function (Blueprint $table) {
            $table->integer('time_addition_subtraction')->default(0)->after('district_id');
            $table->string('am_pm')->nullable()->after('time_addition_subtraction');
        });
    }
};
