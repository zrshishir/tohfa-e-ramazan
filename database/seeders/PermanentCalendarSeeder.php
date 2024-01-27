<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermanentCalendarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

// make the following array according to the $sample

// $permanentCalendars = [

//     [

//         'month_id' => 1,

//         'day' => '01',

//         'sehri' => json_encode(['text_en' => 'Sehri', 'text_bn' => 'সেহরী', 'text_ar' => 'سحري', 'start_time' => '05:16 AM', 'end_time' => '05:16 AM']),

//         'fazr' => json_encode(['text_en' => 'Fazr', 'text_bn' => 'ফজর', 'text_ar' => 'فجر', 'start_time' => '05:16 AM', 'end_time' => '05:16 AM']),

//         'sunrise' => json_encode(['text_en' => 'Sunrise', 'text_bn' => 'সূর্যোদয়', 'text_ar' => 'شروق الشمس', 'start_time' => '05:16 AM', 'end_time' => '05:16 AM']),

//         'johr' => json_encode(['text_en' => 'Johr', 'text_bn' => 'জোহর', 'text_ar' => 'جوهر', 'start_time' => '05:16 AM', 'end_time' => '05:16 AM']),

//         'asr' => json_encode(['text_en' => 'Asr', 'text_bn' => 'আসর', 'text_ar' => 'عصر', 'start_time' => '05:16 AM', 'end_time' => '05:16 AM']),

//         'magrib' => json_encode(['text_en' => 'Magrib', 'text_bn' => 'মাগরিব', 'text_ar' => 'مغرب', 'start_time' => '05:16 AM', 'end_time' => '05:16 AM']),

//         'esha' => json_encode(['text_en' => 'Esha', 'text_bn' => 'ঈশা', 'text_ar' => 'عشاء', 'start_time' => '05:16 AM', 'end_time' => '05:16 AM']),

//         'jummah' => json_encode(['text_en' => 'Jummah', 'text_bn' => 'জুম্মা', 'text_ar' => 'جمعة', 'start_time' => '05:16 AM', 'end_time' => '05:16 AM']), 'tahazzud' => json_encode(['text_en' => 'Tahazzud', 'text_bn' => 'তাহাজ্জুদ', 'text_ar' => 'تهجد', 'start_time' => '05:16 AM', 'end_time' => '05:16 AM']), 'ishraq' => json_encode(['text_en' => 'Ishraq', 'text_bn' => 'ইশরাক', 'text_ar' => 'إشراق', 'start_time' => '05:16 AM', 'end_time' => '05:16 AM']), 'forbidden' => json_encode(['text_en' => 'Forbidden Time', 'text_bn' => 'নিষিদ্ধ সময়', 'text_ar' => 'وقت محظور', 'start_time' => '05:16 AM', 'end_time' => '05:16 AM'])

//     ],

//     [

//         'month_id' => 1,

//         'day' => '02',

//         'sehri' => json_encode(['text_en' => 'Sehri', 'text_bn' => 'সেহরী', 'text_ar' => 'سحري', 'start_time' => '05:17 AM', 'end_time' => '05:17 AM']),

//         'fazr' => json_encode(['text_en' => 'Fazr', 'text_bn' => 'ফজর', 'text_ar' => 'فجر', 'start_time' => '05:17 AM', 'end_time' => '05:17 AM']),

//         'sunrise' => json_encode(['text_en' => 'Sunrise', 'text_bn' => 'সূর্যোদয়', 'text_ar' => 'شروق الشمس', 'start_time' => '05:17 AM', 'end_time' => '05:17 AM']),

//         'johr' => json_encode(['text_en' => 'Johr', 'text_bn' => 'জোহর', 'text_ar' => 'جوهر', 'start_time' => '05:17 AM', 'end_time' => '05:17 AM']),

//         'asr' => json_encode(['text_en' => 'Asr', 'text_bn' => 'আসর', 'text_ar' => 'عصر', 'start_time' => '05:17 AM', 'end_time' => '05:17 AM']),

//         'magrib' => json_encode(['text_en' => 'Magrib', 'text_bn' => 'মাগরিব', 'text_ar' => 'مغرب', 'start_time' => '05:17 AM', 'end_time' => '05:17 AM']),

//         'esha' => json_encode(['text_en' => 'Esha', 'text_bn' => 'ঈশা', 'text_ar' => 'عشاء', 'start_time' => '05:17 AM', 'end_time' => '05:17 AM']),

//         'jummah' => json_encode(['text_en' => 'Jummah', 'text_bn' => 'জুম্মা', 'text_ar' => 'جمعة', 'start_time' => '05:17 AM', 'end_time' => '05:17 AM']), 'tahazzud' => json_encode(['text_en' => 'Tahazzud', 'text_bn' => 'তাহাজ্জুদ', 'text_ar' => 'تهجد', 'start_time' => '05:17 AM', 'end_time' => '05:17 AM']), 'ishraq' => json_encode(['text_en' => 'Ishraq', 'text_bn' => 'ইশরাক', 'text_ar' => 'إشراق', 'start_time' => '05:17 AM', 'end_time' => '05:17 AM']), 'forbidden' => json_encode(['text_en' => 'Forbidden Time', 'text_bn' => 'নিষিদ্ধ সময়', 'text_ar' => 'وقت محظور', 'start_time' => '05:17 AM', 'end_time' => '05:17 AM'])

//     ],

//     [

//         'month_id' => 1,

//         'day' => '03',

//         'sehri' => json_encode(['text_en' => 'Sehri', 'text_bn' => 'সেহরী', 'text_ar' => 'سحري', 'start_time' => '05:18 AM', 'end_time' => '05:18 AM']),

//         'fazr' => json_encode(['text_en' => 'Fazr', 'text_bn' => 'ফজর', 'text_ar' => 'فجر', 'start_time' => '05:18 AM', 'end_time' => '05:18 AM']),

//         'sunrise' => json_encode(['text_en' => 'Sunrise', 'text_bn' => 'সূর্যোদয়', 'text_ar' => 'شروق الشمس', 'start_time' => '05:18 AM', 'end_time' => '05:18 AM']),

//         'johr' => json_encode(['text_en' => 'Johr', 'text_bn' => 'জোহর', 'text_ar' => 'جوهر', 'start_time' => '05:18 AM', 'end_time' => '05:18 AM']),

//         'asr' => json_encode(['text_en' => 'Asr', 'text_bn' => 'আসর', 'text_ar' => 'عصر', 'start_time' => '05:18 AM', 'end_time' => '05:18 AM']),

//         'magrib' => json_encode(['text_en' => 'Magrib', 'text_bn' => 'মাগরিব', 'text_ar' => 'مغرب', 'start_time' => '05:18 AM', 'end_time' => '05:18 AM']),

//         'esha' => json_encode(['text_en' => 'Esha', 'text_bn' => 'ঈশা', 'text_ar' => 'عشاء', 'start_time' => '05:18 AM', 'end_time' => '05:18 AM']),

//         'jummah' => json_encode(['text_en' => 'Jummah', 'text_bn' => 'জুম্মা', 'text_ar' => 'جمعة', 'start_time' => '05:18 AM', 'end_time' => '05:18 AM']), 'tahazzud' => json_encode(['text_en' => 'Tahazzud', 'text_bn' => 'তাহাজ্জুদ', 'text_ar' => 'تهجد', 'start_time' => '05:18 AM', 'end_time' => '05:18 AM']), 'ishraq' => json_encode(['text_en' => 'Ishraq', 'text_bn' => 'ইশরাক', 'text_ar' => 'إشراق', 'start_time' => '05:18 AM', 'end_time' => '05:18 AM']), 'forbidden' => json_encode(['text_en' => 'Forbidden Time', 'text_bn' => 'নিষিদ্ধ সময়', 'text_ar' => 'وقت محظور', 'start_time' => '05:18 AM', 'end_time' => '05:18 AM'])

//     ]

// ];

// Loop through 12 months and insert data into the database
        for ( $monthId = 1; $monthId <= 12; $monthId++ ) {
            // Calculate the number of days dynamically based on your requirements
            $numberOfDays = cal_days_in_month( CAL_GREGORIAN, $monthId, date( 'Y' ) );

            // Generate data for the current month
            $monthData = $this->generatePermanentCalendarData( $monthId, $numberOfDays );

            // Insert data into the database
            DB::table( 'permanent_calendars' )->insert( $monthData );
        }

        // DB::table('permanent_calendars')->insert($permanentCalendars);
    }

    public function generatePermanentCalendarData( $monthId, $numberOfDays )
    {
        $permanentCalendars = [];

        date_default_timezone_set( 'Asia/Dhaka' );

        for ( $day = 1; $day <= $numberOfDays; $day++ ) {
            $sehriTime = date( 'h:i A', strtotime( "03:15 AM +$day minutes" ) );
            $fazrTime = date( 'h:i A', strtotime( "05:00 AM +$day minutes" ) );
            $sunriseTime = date( 'h:i A', strtotime( "05:00 AM +$day minutes" ) );
            $johrTime = date( 'h:i A', strtotime( "01:10 PM +$day minutes" ) );
            $asrTime = date( 'h:i A', strtotime( "04:20 PM +$day minutes" ) );
            $magribTime = date( 'h:i A', strtotime( "05:00 PM +$day minutes" ) );
            $eshaTime = date( 'h:i A', strtotime( "05:00 PM +$day minutes" ) );
            $jummahTime = date( 'h:i A', strtotime( "05:00 PM +$day minutes" ) );
            $tahazzudTime = date( 'h:i A', strtotime( "05:00 PM +$day minutes" ) );
            $ishraqTime = date( 'h:i A', strtotime( "05:00 PM +$day minutes" ) );
            $forbiddenTime = date( 'h:i A', strtotime( "05:00 PM +$day minutes" ) );

            $permanentCalendars[] = [
                'month_id' => $monthId,
                'day' => str_pad( $day, 2, '0', STR_PAD_LEFT ),
                'sehri' => json_encode( ['text_en' => 'Sehri', 'text_bn' => 'সেহরী', 'text_ar' => 'سحري', 'start_time' => $sehriTime, 'end_time' => $sehriTime] ),
                'fazr' => json_encode( ['text_en' => 'Fazr', 'text_bn' => 'ফজর', 'text_ar' => 'فجر', 'start_time' => $fazrTime, 'end_time' => $fazrTime] ),
                'sunrise' => json_encode( ['text_en' => 'Sunrise', 'text_bn' => 'সূর্যোদয়', 'text_ar' => 'شروق الشمس', 'start_time' => $sunriseTime, 'end_time' => $sunriseTime] ),
                'johr' => json_encode( ['text_en' => 'Johr', 'text_bn' => 'জোহর', 'text_ar' => 'جوهر', 'start_time' => $johrTime, 'end_time' => $johrTime] ),
                'asr' => json_encode( ['text_en' => 'Asr', 'text_bn' => 'আসর', 'text_ar' => 'عصر', 'start_time' => $asrTime, 'end_time' => $asrTime] ),
                'magrib' => json_encode( ['text_en' => 'Magrib', 'text_bn' => 'মাগরিব', 'text_ar' => 'مغرب', 'start_time' => $magribTime, 'end_time' => $magribTime] ),
                'esha' => json_encode( ['text_en' => 'Esha', 'text_bn' => 'ঈশা', 'text_ar' => 'عشاء', 'start_time' => $eshaTime, 'end_time' => $eshaTime] ),
                'jummah' => json_encode( ['text_en' => 'Jummah', 'text_bn' => 'জুম্মা', 'text_ar' => 'جمعة', 'start_time' => $jummahTime, 'end_time' => $jummahTime] ),
                'tahazzud' => json_encode( ['text_en' => 'Tahazzud', 'text_bn' => 'তাহাজ্জুদ', 'text_ar' => 'تهجد', 'start_time' => $tahazzudTime, 'end_time' => $tahazzudTime] ),
                'ishraq' => json_encode( ['text_en' => 'Ishraq', 'text_bn' => 'ইশরাক', 'text_ar' => 'إشراق', 'start_time' => $ishraqTime, 'end_time' => $ishraqTime] ),
                'forbidden' => json_encode( ['text_en' => 'Forbidden Time', 'text_bn' => 'নিষিদ্ধ সময়', 'text_ar' => 'وقت محظور', 'start_time' => $forbiddenTime, 'end_time' => $forbiddenTime] ),
            ];
        }

        return $permanentCalendars;
    }

}
