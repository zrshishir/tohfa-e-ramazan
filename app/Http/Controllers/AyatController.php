<?php

namespace App\Http\Controllers;

use App\Models\Ayat;
use Illuminate\Http\Request;
use App\Traits\HelperTrait;

class AyatController extends Controller
{
    use HelperTrait;

    /**
     * GET /api/ayat/{sura_id}
     * Params: page, per_page (default 10, max 100)
     *
     * Previously returned every ayat of a sura in one response — 286 rows for
     * Al-Baqarah — which the reader then had to hold in memory in full.
     */
    public function index(Request $request, $sura_id)
    {
        $perPage = min((int) $request->input('per_page', 10) ?: 10, 100);

        $ayats = Ayat::with('sura')
            ->where('sura_id', $sura_id)
            ->orderBy('ayat_no', 'asc')
            ->paginate($perPage)
            ->withQueryString();

        if ($ayats->isEmpty()) {
            return $this->noContentResponse('Ayats not found', [], 204);
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