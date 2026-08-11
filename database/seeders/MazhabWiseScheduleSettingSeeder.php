<?php

namespace Database\Seeders;

use App\Models\MazhabWiseScheduleSetting;
use Illuminate\Database\Seeder;

/**
 * Every mazhab previously carried a flat offset applied to *every* waqt — Hanafi +15,
 * Shafi'i +10, Maliki +5, Hanbali +7. Since `permanent_calendars` already holds correct
 * published times for Dhaka, that offset was pushing every displayed time late:
 *
 *   published 11 Aug 2026    stored raw    displayed with Hanafi +15
 *   Fajr    4:11 AM          04:10         04:25
 *   Maghrib 6:35 PM          06:35         06:50
 *   Isha    7:56 PM          07:55         08:10
 *
 * Sehri end ran 15 minutes late, and iftar 15 minutes late — both in the direction that
 * invalidates a fast. All offsets are therefore reset to zero, so the app shows the
 * stored published times unmodified.
 *
 * Where the madhhabs genuinely differ:
 *
 *   Asr  — the substantive one. Hanafi: shadow = 2× object length (+ noon shadow);
 *          Maliki, Shafi'i, Hanbali: 1×. Worth 30–90 minutes depending on season and
 *          latitude, so it cannot be expressed as one fixed offset and is left at 0
 *          pending either a computed Asr or verified per-month values.
 *   Zuhr — no difference in when it *starts*. It ends when Asr begins, so the Hanafi
 *          Zuhr window is simply longer; that follows from the Asr rule.
 *   Isha — Abu Hanifa held it begins when the *white* twilight goes; Abu Yusuf,
 *          Muhammad and the other three schools say the *red* twilight, roughly 10–15
 *          minutes earlier. Most Hanafi timetables, including Bangladesh's, follow the
 *          red-twilight position, so 0 matches local practice.
 *
 * Fajr, sunrise and Maghrib are astronomical and identical across all four schools.
 */
class MazhabWiseScheduleSettingSeeder extends Seeder
{
    /**
     * mazhab_id => [johr, asr, esha] offsets in minutes.
     * Everything else is fixed at zero because it does not vary by madhhab.
     */
    private const OFFSETS = [
        1 => [0, 0, 0],   // Hanafi   — asr needs a verified value, see above
        2 => [0, 0, 0],   // Shafi'i
        3 => [0, 0, 0],   // Maliki
        4 => [0, 0, 0],   // Hanbali
    ];

    public function run(): void
    {
        foreach (self::OFFSETS as $mazhabId => [$johr, $asr, $esha]) {
            MazhabWiseScheduleSetting::updateOrCreate(
                ['mazhab_id' => $mazhabId],
                [
                    // Astronomical — no madhhab variation.
                    'sehri_time'  => 0,
                    'fazr_time'   => 0,
                    'ishraq_time' => 0,
                    'magrib_time' => 0,
                    'iftar_time'  => 0,
                    // Genuinely school-dependent.
                    'johr_time'   => $johr,
                    'asr_time'    => $asr,
                    'esha_time'   => $esha,
                ]
            );
        }
    }
}
