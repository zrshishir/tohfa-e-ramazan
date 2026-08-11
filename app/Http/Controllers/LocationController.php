<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Division;
use App\Traits\HelperTrait;

/**
 * Backs the Settings screen's division → district picker.
 *
 * DivisionController and CountryController existed but were never routed, so the
 * app had no way to let a user choose where they are.
 */
class LocationController extends Controller
{
    use HelperTrait;

    /**
     * GET /api/divisions
     * Divisions with their districts, so the picker needs one request.
     */
    public function divisions()
    {
        $divisions = Division::with(['districts' => fn ($q) => $q->orderBy('name')])
            ->orderBy('name')
            ->get();

        if ($divisions->isEmpty()) {
            return $this->noContentResponse('Divisions not found', [], 204);
        }

        return $this->successResponse('Divisions retrieved successfully', $divisions);
    }

    /**
     * GET /api/districts
     * Every district with its division and sehri/iftar offsets.
     */
    public function districts()
    {
        $districts = District::with(['division:id,name', 'districtWiseScheduleSetting'])
            ->orderBy('name')
            ->get();

        if ($districts->isEmpty()) {
            return $this->noContentResponse('Districts not found', [], 204);
        }

        return $this->successResponse('Districts retrieved successfully', $districts);
    }
}
