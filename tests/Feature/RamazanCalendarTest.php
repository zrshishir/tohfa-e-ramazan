<?php

namespace Tests\Feature;

use App\Models\MazhabWiseScheduleSetting;
use App\Models\Month;
use App\Models\PermanentCalendar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RamazanCalendarTest extends TestCase
{
    use RefreshDatabase;

    private function prayer(string $label, string $start, string $end): array
    {
        return [
            'text_en'    => $label,
            'text_bn'    => $label,
            'text_ar'    => $label,
            'start_time' => $start,
            'end_time'   => $end,
        ];
    }

    private function seedCalendar(): void
    {
        for ($month = 1; $month <= 12; $month++) {
            Month::create(['id' => $month, 'name' => 'Month ' . $month]);

            for ($day = 1; $day <= 31; $day++) {
                PermanentCalendar::create([
                    'month_id'  => $month,
                    'day'       => (string) $day,
                    'sehri'     => $this->prayer('Sehri',  '04:39 AM', '05:22 AM'),
                    'fazr'      => $this->prayer('Fazr',   '05:22 AM', '06:00 AM'),
                    'sunrise'   => $this->prayer('Sunrise','06:00 AM', '06:01 AM'),
                    'ishraq'    => $this->prayer('Ishraq', '06:20 AM', '07:00 AM'),
                    'johr'      => $this->prayer('Johr',   '12:00 PM', '01:00 PM'),
                    'asr'       => $this->prayer('Asr',    '04:00 PM', '05:00 PM'),
                    'magrib'    => $this->prayer('Magrib', '05:27 PM', '06:45 PM'),
                    'esha'      => $this->prayer('Esha',   '07:00 PM', '11:00 PM'),
                    'tahazzud'  => $this->prayer('Tahazzud','01:00 AM','04:00 AM'),
                    'jummah'    => $this->prayer('Jummah', '01:00 PM', '02:00 PM'),
                    'forbidden' => $this->prayer('Forbidden','12:00 PM','12:05 PM'),
                ]);
            }
        }
    }

    private function seedMazhab(int $iftarOffset = 15, int $magribOffset = 15): void
    {
        $user = User::create([
            'name'     => 'Seeder',
            'email'    => 'seeder@example.com',
            'password' => bcrypt('password'),
        ]);

        // Written directly: the Mazhab model's $fillable covers neither id nor user_id.
        DB::table('mazhabs')->insert([
            'id'          => 1,
            'user_id'     => $user->id,
            'name'        => 'Hanafi',
            'bangla_text' => 'হানাফি',
            'arabic_text' => 'حنفي',
        ]);

        MazhabWiseScheduleSetting::create([
            'mazhab_id'   => 1,
            'sehri_time'  => 15,
            'fazr_time'   => 15,
            'ishraq_time' => 15,
            'johr_time'   => 15,
            'asr_time'    => 15,
            'magrib_time' => $magribOffset,
            'iftar_time'  => $iftarOffset,
            'esha_time'   => 15,
        ]);
    }

    // ------------------------------------------------------------- regression

    public function test_ramazan_calendar_does_not_500(): void
    {
        $this->seedCalendar();
        $this->seedMazhab();

        // Regression: the query named a non-existent `iftar` column, so every request
        // died with "Unknown column 'iftar' in 'field list'".
        $this->getJson('/api/ramazan-calendar')
            ->assertOk()
            ->assertJsonPath('status', 'success');
    }

    public function test_ramazan_calendar_returns_thirty_days(): void
    {
        $this->seedCalendar();
        $this->seedMazhab();

        $response = $this->getJson('/api/ramazan-calendar?day=1&month_id=1')->assertOk();

        $this->assertCount(30, $response->json('data.permanent_calendars'));
    }

    public function test_each_entry_carries_a_derived_iftar(): void
    {
        $this->seedCalendar();
        $this->seedMazhab();

        $response = $this->getJson('/api/ramazan-calendar?day=1&month_id=1')->assertOk();

        $first = $response->json('data.permanent_calendars.0');

        $this->assertArrayHasKey('iftar', $first);
        $this->assertSame('Iftar', $first['iftar']['text_en']);
        // magrib start 05:27 PM + iftar_time 15 = 05:42 PM
        $this->assertSame('05:27 PM', $first['iftar']['start_time']);
    }

    public function test_magrib_and_iftar_carry_no_mazhab_offset(): void
    {
        $this->seedCalendar();
        // Non-zero legacy values: neither waqt may move, both being astronomical.
        $this->seedMazhab(iftarOffset: 5, magribOffset: 30);

        $first = $this->getJson('/api/ramazan-calendar?day=1&month_id=1')
            ->assertOk()
            ->json('data.permanent_calendars.0');

        $this->assertSame('05:27 PM', $first['magrib']['start_time']);
        $this->assertSame('05:27 PM', $first['iftar']['start_time']);
    }

    public function test_month_rollover_is_handled(): void
    {
        $this->seedCalendar();
        $this->seedMazhab();

        $entries = $this->getJson('/api/ramazan-calendar?day=25&month_id=1')
            ->assertOk()
            ->json('data.permanent_calendars');

        $this->assertCount(30, $entries);
        $months = array_unique(array_column($entries, 'month_id'));
        $this->assertGreaterThan(1, count($months), 'Starting on the 25th must span two months');
    }

    // ----------------------------------------------- other calendar endpoints

    public function test_today_prayer_also_exposes_iftar(): void
    {
        $this->seedCalendar();
        $this->seedMazhab();

        $times = $this->getJson('/api/today-prayer?day=1&month_id=1')
            ->assertOk()
            ->json('data.prayer_times');

        $this->assertArrayHasKey('iftar', $times);
        $this->assertSame('05:27 PM', $times['iftar']['start_time']);
    }

    public function test_permanent_calendar_index_also_exposes_iftar(): void
    {
        $this->seedCalendar();
        $this->seedMazhab();

        $first = $this->postJson('/api/permanent-calendar', ['month_id' => 1])
            ->assertOk()
            ->json('data.permanent_calendars.data.0');

        $this->assertArrayHasKey('iftar', $first);
        $this->assertSame('05:27 PM', $first['iftar']['start_time']);
    }

    public function test_by_month_also_exposes_iftar(): void
    {
        $this->seedCalendar();
        $this->seedMazhab();

        $first = $this->getJson('/api/permanent-calendar/1')
            ->assertOk()
            ->json('data.permanent_calendars.0');

        $this->assertArrayHasKey('iftar', $first);
        $this->assertSame('05:27 PM', $first['iftar']['start_time']);
    }

    public function test_works_without_a_mazhab_setting(): void
    {
        $this->seedCalendar();

        // No MazhabWiseScheduleSetting row at all — must not fatal.
        $this->getJson('/api/ramazan-calendar?day=1&month_id=1')->assertOk();
    }
}
