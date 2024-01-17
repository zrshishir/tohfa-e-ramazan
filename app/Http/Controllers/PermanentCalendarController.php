<?php

namespace App\Http\Controllers;

use App\Models\PermanentCalendar;
use Illuminate\Http\Request;

class PermanentCalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        date_default_timezone_set('Asia/Dhaka');
        $date = date('Y-m-d');
        $month = date('m', strtotime($date));
        $day = date('d', strtotime($date));

        $mazhabId = 1;
        $mazhabSetting = MazhabSetting::where('mazhab_id', $mazhabId)->first();
        $permanentCalendars = PermanentCalendar::where('month_id', '>=', $month)->where('day', '>=', $day)->paginate(30);


        return response()->json([
            'status' => 'success',
            'status_code' => 200,
            'message' => 'Permanent Calendar Data',
            'data' => [
                'mazhab_setting' => $mazhabSetting,
                'permanent_calendars' => $permanentCalendars,
            ]
        ]);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PermanentCalendar $permanentCalendar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PermanentCalendar $permanentCalendar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PermanentCalendar $permanentCalendar)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PermanentCalendar $permanentCalendar)
    {
        //
    }
}
