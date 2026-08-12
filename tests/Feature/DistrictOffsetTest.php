<?php

namespace Tests\Feature;

use App\Models\District;
use App\Models\DistrictWiseScheduleSetting;
use App\Models\Division;
use App\Models\MazhabWiseScheduleSetting;
use App\Models\Month;
use App\Models\PermanentCalendar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DistrictOffsetTest extends TestCase
{
    use RefreshDatabase;

    private District $dhaka;
    private District $sylhet;

    protected function setUp(): void
    {
        parent::setUp();

        DB::table('countries')->insert(['id' => 1, 'iso' => 'BD', 'name' => 'BANGLADESH',
            'nice_name' => 'Bangladesh', 'iso3' => 'BGD', 'num_code' => 50, 'phone_code' => 880]);

        $division = Division::create(['name' => 'Dhaka', 'country_id' => 1]);

        $this->dhaka  = District::create(['name' => 'Dhaka', 'division_id' => $division->id]);
        $this->sylhet = District::create(['name' => 'Sylhet', 'division_id' => $division->id]);

        DistrictWiseScheduleSetting::create([
            'district_id' => $this->dhaka->id, 'sehri_offset' => 0, 'iftar_offset' => 0, 'is_active' => true,
        ]);
        // Sylhet is the widest split in the table: -9 sehri, -4 iftar.
        DistrictWiseScheduleSetting::create([
            'district_id' => $this->sylhet->id, 'sehri_offset' => -9, 'iftar_offset' => -4, 'is_active' => true,
        ]);

        $user = User::create(['name' => 'S', 'email' => 's@example.com', 'password' => bcrypt('x')]);
        DB::table('mazhabs')->insert(['id' => 1, 'user_id' => $user->id, 'name' => 'Hanafi',
            'bangla_text' => 'হানাফি', 'arabic_text' => 'حنفي']);

        // Zero mazhab offsets so the district shift is the only thing moving times.
        MazhabWiseScheduleSetting::create([
            'mazhab_id' => 1, 'sehri_time' => 0, 'fazr_time' => 0, 'ishraq_time' => 0,
            'johr_time' => 0, 'asr_time' => 0, 'magrib_time' => 0, 'iftar_time' => 0, 'esha_time' => 0,
        ]);

        Month::create(['id' => 1, 'name' => 'January']);

        $prayer = fn ($label, $start, $end) => [
            'text_en' => $label, 'text_bn' => $label, 'text_ar' => $label,
            'start_time' => $start, 'end_time' => $end,
        ];

        PermanentCalendar::create([
            'month_id' => 1, 'day' => '1',
            'sehri'   => $prayer('Sehri', '04:39 AM', '05:22 AM'),
            'fazr'    => $prayer('Fazr', '05:22 AM', '06:00 AM'),
            'sunrise' => $prayer('Sunrise', '06:00 AM', '06:01 AM'),
            'ishraq'  => $prayer('Ishraq', '06:20 AM', '07:00 AM'),
            'johr'    => $prayer('Johr', '12:00 PM', '01:00 PM'),
            'asr'     => $prayer('Asr', '04:00 PM', '05:00 PM'),
            'magrib'  => $prayer('Magrib', '05:27 PM', '06:45 PM'),
            'esha'    => $prayer('Esha', '07:00 PM', '11:00 PM'),
            'tahazzud' => $prayer('Tahazzud', '01:00 AM', '04:00 AM'),
            'jummah'  => $prayer('Jummah', '01:00 PM', '02:00 PM'),
            'forbidden' => $prayer('Forbidden', '12:00 PM', '12:05 PM'),
        ]);
    }

    private function times(?int $districtId = null): array
    {
        $query = 'day=1&month_id=1' . ($districtId ? "&district_id={$districtId}" : '');

        return $this->getJson("/api/today-prayer?{$query}")
            ->assertOk()
            ->json('data.prayer_times');
    }

    public function test_no_district_leaves_times_at_the_dhaka_baseline(): void
    {
        $times = $this->times();

        $this->assertSame('05:22 AM', $times['sehri']['end_time']);
        $this->assertSame('05:27 PM', $times['iftar']['start_time']);
    }

    public function test_sehri_and_iftar_shift_independently(): void
    {
        $times = $this->times($this->sylhet->id);

        // Regression guard: the old schema had a single offset column and could not
        // have produced two different shifts.
        $this->assertSame('05:13 AM', $times['sehri']['end_time']);   // -9
        $this->assertSame('05:23 PM', $times['iftar']['start_time']); // -4
    }

    public function test_a_zero_offset_district_matches_the_baseline(): void
    {
        $this->assertSame($this->times(), $this->times($this->dhaka->id));
    }

    public function test_district_does_not_shift_other_waqts(): void
    {
        $times = $this->times($this->sylhet->id);

        // Only sehri and iftar are district-adjusted; magrib, fazr and the rest are not.
        $this->assertSame('05:22 AM', $times['fazr']['start_time']);
        $this->assertSame('05:27 PM', $times['magrib']['start_time']);
        $this->assertSame('04:00 PM', $times['asr']['start_time']);
    }

    public function test_an_inactive_setting_is_ignored(): void
    {
        DistrictWiseScheduleSetting::where('district_id', $this->sylhet->id)
            ->update(['is_active' => false]);

        $this->assertSame('05:22 AM', $this->times($this->sylhet->id)['sehri']['end_time']);
    }

    public function test_an_unknown_district_falls_back_to_the_baseline(): void
    {
        $this->assertSame('05:22 AM', $this->times(9999)['sehri']['end_time']);
    }

    public function test_the_offset_applies_across_every_calendar_endpoint(): void
    {
        $ramazan = $this->getJson("/api/ramazan-calendar?day=1&month_id=1&district_id={$this->sylhet->id}")
            ->assertOk()->json('data.permanent_calendars.0');
        $this->assertSame('05:13 AM', $ramazan['sehri']['end_time']);
        $this->assertSame('05:23 PM', $ramazan['iftar']['start_time']);

        $byMonth = $this->getJson("/api/permanent-calendar/1?district_id={$this->sylhet->id}")
            ->assertOk()->json('data.permanent_calendars.0');
        $this->assertSame('05:13 AM', $byMonth['sehri']['end_time']);

        $index = $this->postJson('/api/permanent-calendar', ['month_id' => 1, 'district_id' => $this->sylhet->id])
            ->assertOk()->json('data.permanent_calendars.data.0');
        $this->assertSame('05:13 AM', $index['sehri']['end_time']);
    }

    // ------------------------------------------------------------- endpoints

    public function test_divisions_endpoint_embeds_districts(): void
    {
        $this->getJson('/api/divisions')
            ->assertOk()
            ->assertJsonPath('data.0.name', 'Dhaka')
            ->assertJsonCount(2, 'data.0.districts');
    }

    public function test_districts_endpoint_returns_offsets(): void
    {
        $districts = $this->getJson('/api/districts')->assertOk()->json('data');

        $sylhet = collect($districts)->firstWhere('name', 'Sylhet');

        $this->assertSame('Dhaka', $sylhet['division']['name']);
        $this->assertSame(-9, $sylhet['district_wise_schedule_setting'][0]['sehri_offset']);
        $this->assertSame(-4, $sylhet['district_wise_schedule_setting'][0]['iftar_offset']);
    }
}
