<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doa;

class DoaController extends Controller
{
    // write all methods for restful api
    public function index()
    {
        $doas = Doa::all();

        return response()->json([
            'success' => true,
            'message' => 'Doas Index',
            'data' => $doas,
        ]);
    }
}