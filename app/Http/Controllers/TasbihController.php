<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tasbih;

class TasbihController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasbih = Tasbih::first();

        return response()->json([
            'status' => 'success',
            'status_code' => 200,
            'message' => 'Tasbih Data',
            'data' => $tasbih,
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
        // validate json data
        $validator = $request->validate([
            'user_id' => 'required',
            'subhanallah' => 'required',
            'alhamdulillah' => 'required',
            'allahuakbar' => 'required',
            'astagfirullah' => 'required',
            'la_ilaha_illallah' => 'required',
            'subhanallahi_wabi_hamdihi_wa_subhanallahil_azeem' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'status_code' => 400,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ]);
        }

        $tasbih = Tasbih::create($request->all());

        return response()->json([
            'status' => 'success',
            'status_code' => 200,
            'message' => 'Tasbih Data',
            'data' => $tasbih,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // show tasbih data by user id
        $tasbih = Tasbih::where('user_id', $id)->first();

        return response()->json([
            'status' => 'success',
            'status_code' => 200,
            'message' => 'Tasbih Data',
            'data' => $tasbih,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tasbih = Tasbih::where('user_id', $id)->first();

        return response()->json([
            'status' => 'success',
            'status_code' => 200,
            'message' => 'Tasbih Data',
            'data' => $tasbih,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // validate json data
        $validator = $request->validate([
            'user_id' => 'required',
            'subhanallah' => 'required',
            'alhamdulillah' => 'required',
            'allahuakbar' => 'required',
            'astagfirullah' => 'required',
            'la_ilaha_illallah' => 'required',
            'subhanallahi_wabi_hamdihi_wa_subhanallahil_azeem' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'status_code' => 400,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ]);
        }

        $tasbih = Tasbih::where('user_id', $id)->first();
        $tasbih->update($request->all());

        return response()->json([
            'status' => 'success',
            'status_code' => 200,
            'message' => 'Tasbih Data',
            'data' => $tasbih,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tasbih = Tasbih::where('user_id', $id)->first();
        $tasbih->delete();

        return response()->json([
            'status' => 'success',
            'status_code' => 200,
            'message' => 'Tasbih Data',
            'data' => $tasbih,
        ]);
    }
}