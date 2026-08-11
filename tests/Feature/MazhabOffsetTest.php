<?php

namespace Tests\Feature;

use App\Models\MazhabWiseScheduleSetting;
use App\Models\PermanentCalendar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Guards the correction to a live bug: a mazhab's flat offset was applied to every
 * waqt, shifting sehri end and iftar late against the correct published times already
 * stored in permanent_calendars. Both moved in the direction that invalidates a fast.
 */
class MazhabOffsetTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::create(['name' => 'M', 'email' => 'm@example.com', 'password' => bcrypt('x')]);
        DB::table('mazhabs')->insert(['id' => 1, 'user_id' => $user->id, 'name' => 'Hanafi',
            'bangla_text' => 'হানাফি', 'arabic_text' => 'حنفي']);

        // Month::$fillable omits id, so a direct insert is needed to control it.
        DB::table('months')->insert(['id' => 8, 'name' => 'August']);

        $prayer = fn ($label, $start, $end) => [
            'text_en' => $label, 'text_bn' => $label, 'text_ar' => $label,
            'start_time' => $start, 'end_time' => $end,
        ];

        // Published Dhaka times for 11 August 2026.
        PermanentCalendar::create([
            'month_id' => 8, 'day' => '11',
            'sehri'   => $prayer('Sehri', '03:27 AM', '04:10 AM'),
            'fazr'    => $prayer('Fazr', '04:10 AM', '05:29 AM'),
            'sunrise' => $prayer('Sunrise', '05:29 AM', '05:30 AM'),
            'ishraq'  => $prayer('Ishraq', '05:50 AM', '11:00 AM'),
            'johr'    => $prayer('Johr', '12:03 PM', '04:18 PM'),
            'asr'     => $prayer('Asr', '04:33 PM', '06:13 PM'),
            'magrib'  => $prayer('Magrib', '06:35 PM', '07:55 PM'),
            'esha'    => $prayer('Esha', '07:55 PM', '11:00 PM'),
            'tahazzud' => $prayer('Tahazzud', '01:00 AM', '04:00 AM'),
            'jummah'  => $prayer('Jummah', '01:00 PM', '02:00 PM'),
            'forbidden' => $prayer('Forbidden', '12:00 PM', '12:05 PM'),
        ]);
    }

    private function seedOffsets(array $overrides = []): void
    {
        MazhabWiseScheduleSetting::create(array_merge([
            'mazhab_id' => 1, 'sehri_time' => 0, 'fazr_time' => 0, 'ishraq_time' => 0,
            'johr_time' => 0, 'asr_time' => 0, 'magrib_time' => 0, 'iftar_time' => 0,
            'esha_time' => 0,
        ], $overrides));
    }

    private function times(): array
    {
        return $this->getJson('/api/today-prayer?day=11&month_id=8')
            ->assertOk()
            ->json('data.prayer_times');
    }

    public function test_times_match_the_stored_published_values(): void
    {
        $this->seedOffsets();
        $times = $this->times();

        $this->assertSame('04:10 AM', $times['sehri']['end_time']);
        $this->assertSame('04:10 AM', $times['fazr']['start_time']);
        $this->assertSame('06:35 PM', $times['magrib']['start_time']);
        $this->assertSame('06:35 PM', $times['iftar']['start_time']);
        $this->assertSame('07:55 PM', $times['esha']['start_time']);
    }

    public function test_a_mazhab_offset_can_no_longer_move_sehri_or_iftar(): void
    {
        // Even with a non-zero legacy value, these waqts must not shift: they are
        // astronomical and identical across all four schools.
        $this->seedOffsets(['sehri_time' => 15, 'magrib_time' => 15, 'iftar_time' => 15, 'fazr_time' => 15]);

        $times = $this->times();

        $this->assertSame('04:10 AM', $times['sehri']['end_time'], 'sehri must not carry a mazhab offset');
        $this->assertSame('04:10 AM', $times['fazr']['start_time'], 'fazr must not carry a mazhab offset');
        $this->assertSame('06:35 PM', $times['magrib']['start_time'], 'magrib must not carry a mazhab offset');
        $this->assertSame('06:35 PM', $times['iftar']['start_time'], 'iftar must not carry a mazhab offset');
    }

    public function test_the_school_dependent_waqts_still_respond(): void
    {
        $this->seedOffsets(['johr_time' => 2, 'asr_time' => 45, 'esha_time' => 10]);

        $times = $this->times();

        $this->assertSame('12:05 PM', $times['johr']['start_time']);
        $this->assertSame('05:18 PM', $times['asr']['start_time']);   // Hanafi 2x shadow sits far later
        $this->assertSame('08:05 PM', $times['esha']['start_time']);
    }

    public function test_the_ramazan_calendar_is_also_unshifted(): void
    {
        $this->seedOffsets(['sehri_time' => 15, 'magrib_time' => 15, 'iftar_time' => 15]);

        $entry = $this->getJson('/api/ramazan-calendar?day=11&month_id=8')
            ->assertOk()
            ->json('data.permanent_calendars.0');

        $this->assertSame('04:10 AM', $entry['sehri']['end_time']);
        $this->assertSame('06:35 PM', $entry['iftar']['start_time']);
    }
}
