<?php

namespace App\Http\Controllers;

use App\Models\MazhabWiseScheduleSetting;
use App\Models\PermanentCalendar;
use Illuminate\Http\Request;

class PermanentCalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request )
    {

        date_default_timezone_set( 'Asia/Dhaka' );

        $to = $request->input( 'to' );
        $mazhabId = $request->input( 'mazhab_id', 1 );
        $date = date( 'Y-m-d' );
        $today = date( 'd', strtotime( $date ) );
        $month = date( 'm', strtotime( $date ) );
        $month_id = $request->input( 'month_id', $month );

        


        $nextDay = $to ?? date( 'd', strtotime( 'tomorrow' ));
        $mazhabSetting = MazhabWiseScheduleSetting::where( 'mazhab_id', $mazhabId )->first();
        $permanentCalendars = PermanentCalendar::where( 'month_id', '>=', $month_id )->whereBetween( 'day', [$today, $nextDay] )->paginate( 30 );

        return response()->json( [
            'status' => 'success',
            'today' => $today,
            'nextDay' =>  $nextDay,
            'status_code' => 200,
            'message' => 'Permanent Calendar Data',
            'data' => [
                'mazhab_setting' => $mazhabSetting,
                'permanent_calendars' => $permanentCalendars,
            ],
        ] );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store( Request $request )
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show( PermanentCalendar $permanentCalendar )
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( PermanentCalendar $permanentCalendar )
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update( Request $request, PermanentCalendar $permanentCalendar )
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( PermanentCalendar $permanentCalendar )
    {
        //
    }

}
