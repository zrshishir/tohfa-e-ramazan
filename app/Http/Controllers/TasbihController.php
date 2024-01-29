<?php

namespace App\Http\Controllers;

use App\Models\Tasbih;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;

class TasbihController extends Controller
{
    use HelperTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $tasbih = Tasbih::where('user_id', auth()->user()->id)->first();
        $tasbih = Tasbih::first();

        if (empty($tasbih)) {

            return $this->noContentResponse('No tasbih data found', $tasbih, 204);
        }

        return $this->successResponse('Tasbih Data', $tasbih, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate json data
        $validator = $request->validate([
            'user_id'                                          => 'required',
            'subhanallah'                                      => 'required',
            'alhamdulillah'                                    => 'required',
            'allahuakbar'                                      => 'required',
            'astagfirullah'                                    => 'required',
            'la_ilaha_illallah'                                => 'required',
            'subhanallahi_wabi_hamdihi_wa_subhanallahil_azeem' => 'required',
        ]);

        $tasbih = Tasbih::where('user_id', $request->user_id)->first();

        if ($tasbih) {
            $tasbih->update($request->all());
        } else {
            $tasbih = Tasbih::create($request->all());
        }

        return $this->successResponse('Your tasbih data has been updated.', $tasbih, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // show tasbih data by user id
        $tasbih = Tasbih::where('user_id', $id)->first();

        if (empty($tasbih)) {

            return $this->noContentResponse('No tasbih data found', $tasbih, 204);
        }

        return $this->successResponse('Your tasbih Data', $tasbih, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tasbih = Tasbih::where('user_id', $id)->first();

        return $this->successResponse('Tasbih Data', $tasbih, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // validate json data
        $validator = $request->validate([
            'user_id'                                                 => 'required',
            'tasbih'                                                  => 'required',
            'tasbih.subhanallah'                                      => 'required',
            'tasbih.alhamdulillah'                                    => 'required',
            'tasbih.allahuakbar'                                      => 'required',
            'tasbih.astagfirullah'                                    => 'required',
            'tasbih.la_ilaha_illallah'                                => 'required',
            'tasbih.subhanallahi_wabi_hamdihi_wa_subhanallahil_azeem' => 'required',
        ]);

        if ($validator->fails()) {

            return $this->validationErrorResponse('Validation Error', $validator->errors(), 400);
        }

        $tasbih = Tasbih::where('user_id', $id)->first();
        $tasbih->update($request->all());

        return $this->successResponse('Your tasbeeh data has been updated.', $tasbih, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tasbih = Tasbih::where('user_id', $id)->first();
        $tasbih->delete();

        return $this->successResponse('Your tasbih has been deleted', $tasbih, 200);
    }
}