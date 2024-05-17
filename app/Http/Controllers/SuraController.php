<?php

namespace App\Http\Controllers;

use App\Models\Sura;
use Illuminate\Http\Request;

class SuraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suras = Sura::all();

        return response()->json([
            'success' => true,
            'message' => 'Suras Index',
            'data' => $suras,
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
    public function show(Sura $sura)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sura $sura)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sura $sura)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sura $sura)
    {
        //
    }
}