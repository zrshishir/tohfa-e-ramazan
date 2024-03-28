<?php

namespace App\Http\Controllers;

use App\Models\AsmaulHusna;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;

class AsmaulHusnaController extends Controller
{
    use HelperTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->successResponse("Asmaul Husna", AsmaulHusna::all(), 200);
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
    public function show(AsmaulHusna $asmaulHusna)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AsmaulHusna $asmaulHusna)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AsmaulHusna $asmaulHusna)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AsmaulHusna $asmaulHusna)
    {
        //
    }
}
