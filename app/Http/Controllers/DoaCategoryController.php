<?php

namespace App\Http\Controllers;

use App\Models\DoaCategory;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\JsonResponse;

class DoaCategoryController extends Controller
{
    /**
     * tasbih function
     *
     * @return json response
     */
    public function index(): JsonResponse
    {
        $doaCategories = DoaCategory::all();

        return response()->json([
            'success' => true,
            'message' => 'Doa Categories Index',
            'data'    => $doaCategories,
        ]);
    }
}