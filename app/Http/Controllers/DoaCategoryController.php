<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DoaCategory;

class DoaCategoryController extends Controller
{
    // write index function for doa category
    public function index()
    {
        $doaCategories = DoaCategory::all();

        return response()->json([
            'success' => true,
            'message' => 'Doa Categories Index',
            'data' => $doaCategories,
        ]);
    }
}