<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\DistrictWiseScheduleSetting;
use App\Models\Division;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeds the district table to all 64 districts across all 8 divisions, and populates
 * the Islamic Foundation sehri/iftar offsets relative to Dhaka.
 *
 * ⚠️ The offsets come from a secondary source citing the Islamic Foundation
 * (iqbir.com), not scraped from islamicfoundation.gov.bd directly. They are editable
 * in the Filament admin and should be spot-checked before release.
 *
 * Note that for Ramadan 2026 the Islamic Foundation moved to publishing 64 separate
 * district schedules rather than offsets. The offset model is retained here because
 * this app serves a year-round permanent calendar, which IF does not publish per
 * district.
 */
class DistrictWiseScheduleSettingSeeder extends Seeder
{
    /**
     * The districts table shipped with three defects, all corrected here:
     *   - division 5 was labelled "Barisal" but held every Rangpur district
     *   - division 7 "Rangpur" was empty
     *   - Barisal's own 6 districts and the whole Mymensingh division were missing
     */
    private const DIVISIONS = [
        1 => 'Dhaka',
        2 => 'Chittagong',
        3 => 'Khulna',
        4 => 'Rajshahi',
        5 => 'Barisal',
        6 => 'Sylhet',
        7 => 'Rangpur',
        8 => 'Mymensingh',
    ];

    /** division id => district names that belong to it */
    private const DIVISION_DISTRICTS = [
        5 => ['Barguna', 'Barisal', 'Bhola', 'Jhalokati', 'Patuakhali', 'Pirojpur'],
        7 => ['Dinajpur', 'Gaibandha', 'Kurigram', 'Lalmonirhat', 'Nilphamari',
              'Panchagarh', 'Rangpur', 'Thakurgaon'],
        8 => ['Mymensingh', 'Jamalpur', 'Netrokona', 'Sherpur'],
    ];

    /**
     * district => [sehri offset, iftar offset] in minutes relative to Dhaka.
     * Dhaka itself is the 0/0 baseline.
     */
    private const OFFSETS = [
        'Dhaka' => [0, 0],
        "Cox's Bazar" => [-1, -10], 'Kishoreganj' => [-3, -1], 'Kurigram' => [-2, 7],
        'Comilla' => [-3, -4], 'Kushtia' => [5, 5], 'Khagrachari' => [-5, -8],
        'Khulna' => [6, 2], 'Gaibandha' => [-1, 6], 'Gazipur' => [-1, 0],
        'Gopalganj' => [4, 1], 'Chittagong' => [-2, -8], 'Chandpur' => [0, -2],
        'Nawabganj' => [6, 10], 'Chuadanga' => [6, 6], 'Joypurhat' => [2, 8],
        'Jamalpur' => [-2, 4], 'Jhalokati' => [3, -1], 'Jhenaidah' => [5, 5],
        'Tangail' => [0, 2], 'Thakurgaon' => [2, 12], 'Dinajpur' => [2, 10],
        'Naogaon' => [3, 8], 'Narail' => [5, 2], 'Narsingdi' => [-2, -1],
        'Natore' => [4, 7], 'Narayanganj' => [0, -1], 'Nilphamari' => [1, 10],
        'Netrokona' => [-5, 0], 'Noakhali' => [-1, -4], 'Panchagarh' => [1, 12],
        'Patuakhali' => [4, -2], 'Pabna' => [4, 5], 'Pirojpur' => [5, 0],
        'Faridpur' => [2, 2], 'Feni' => [-3, -5], 'Bogra' => [1, 6],
        'Barguna' => [5, -2], 'Barisal' => [2, -2], 'Bagerhat' => [5, 1],
        'Bandarban' => [-4, -10], 'Brahmanbaria' => [-4, -3], 'Bhola' => [2, -3],
        'Mymensingh' => [-3, 1], 'Magura' => [4, 3], 'Madaripur' => [2, 0],
        'Manikganj' => [1, 2], 'Munshiganj' => [0, -1], 'Meherpur' => [7, 7],
        'Moulvibazar' => [-8, -4], 'Jessore' => [6, 4], 'Rangpur' => [-1, 8],
        'Rangamati' => [-4, -9], 'Rajbari' => [4, 4], 'Rajshahi' => [5, 8],
        'Lakshmipur' => [-1, -3], 'Lalmonirhat' => [-2, 7], 'Shariatpur' => [2, -1],
        'Sherpur' => [-2, 3], 'Satkhira' => [8, 4], 'Sirajganj' => [1, 4],
        'Sylhet' => [-9, -4], 'Sunamganj' => [-7, -2], 'Habiganj' => [-6, -3],
    ];

    public function run(): void
    {
        $this->ensureDivisions();
        $this->relocateRangpurDistricts();
        $this->addMissingDistricts();
        $this->seedOffsets();
    }

    private function ensureDivisions(): void
    {
        $countryId = DB::table('countries')->where('nice_name', 'Bangladesh')->value('id')
            ?? DB::table('countries')->value('id');

        foreach (self::DIVISIONS as $id => $name) {
            Division::updateOrCreate(['id' => $id], ['name' => $name, 'country_id' => $countryId]);
        }
    }

    /** They were sitting under division 5, which is Barisal. */
    private function relocateRangpurDistricts(): void
    {
        District::whereIn('name', self::DIVISION_DISTRICTS[7])->update(['division_id' => 7]);
    }

    private function addMissingDistricts(): void
    {
        foreach ([5, 8] as $divisionId) {
            foreach (self::DIVISION_DISTRICTS[$divisionId] as $name) {
                District::updateOrCreate(['name' => $name], ['division_id' => $divisionId]);
            }
        }
    }

    private function seedOffsets(): void
    {
        $missing = [];

        foreach (self::OFFSETS as $name => [$sehri, $iftar]) {
            $district = District::where('name', $name)->first();

            if (!$district) {
                $missing[] = $name;
                continue;
            }

            DistrictWiseScheduleSetting::updateOrCreate(
                ['district_id' => $district->id],
                ['sehri_offset' => $sehri, 'iftar_offset' => $iftar, 'is_active' => true]
            );
        }

        if ($missing) {
            $this->command?->warn('No district row for: ' . implode(', ', $missing));
        }

        $this->command?->info(
            DistrictWiseScheduleSetting::count() . ' district offsets seeded across '
            . District::count() . ' districts.'
        );
    }
}
