<?php

namespace App\Http\Controllers;

use App\Models\Hadith;
use App\Traits\HelperTrait;

class HadithController extends Controller
{
    use HelperTrait;

    /**
     * GET /api/hadith
     * Active hadiths, newest first.
     */
    public function index()
    {
        $hadiths = Hadith::where('status', true)
            ->orderBy('id', 'asc')
            ->get();

        if ($hadiths->isEmpty()) {
            return $this->noContentResponse('Hadiths not found', [], 204);
        }

        return $this->successResponse('Hadiths retrieved successfully', $hadiths);
    }

    /**
     * GET /api/hadith/{id}
     */
    public function show(int $id)
    {
        $hadith = Hadith::where('status', true)->find($id);

        if (!$hadith) {
            return $this->notFoundResponse('Hadith not found', []);
        }

        return $this->successResponse('Hadith retrieved successfully', $hadith);
    }
}
