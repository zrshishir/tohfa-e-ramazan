<?php

namespace App\Http\Controllers;

use App\Models\Ayat;
use Illuminate\Http\Request;
use App\Traits\HelperTrait;

class AyatController extends Controller
{
    use HelperTrait;
    /**
     * Display a listing of the resource.
     */
    public function index($sura_id)
    {
        $ayats = Ayat::with('sura')->where('sura_id', $sura_id)->get();

        if (empty($ayats)) {

            return $this->noContentResponse($message = 'Ayats not found', $data = [], $statusCode = 204);
        }

        return $this->successResponse('Ayats retrieved successfully', $ayats);
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
    public function show(Ayat $ayat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ayat $ayat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ayat $ayat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ayat $ayat)
    {
        //
    }
}