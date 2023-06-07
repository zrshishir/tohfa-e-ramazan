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
        return response()->json([
            'status' => 'success',
            'status_code' => 200,
            'message' => 'Permanent Calendar Data',
            'data' => PermanentCalendar::all(),
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
