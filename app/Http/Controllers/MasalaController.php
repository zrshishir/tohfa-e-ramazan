<?php

namespace App\Http\Controllers;

use App\Models\Masala;
use App\Traits\HelperTrait;

class MasalaController extends Controller
{
    use HelperTrait;

    /**
     * GET /api/masala
     * Active masalas, in insertion order.
     */
    public function index()
    {
        $masalas = Masala::where('status', true)
            ->orderBy('id', 'asc')
            ->get();

        if ($masalas->isEmpty()) {
            return $this->noContentResponse('Masalas not found', [], 204);
        }

        return $this->successResponse('Masalas retrieved successfully', $masalas);
    }

    /**
     * GET /api/masala/{id}
     */
    public function show(int $id)
    {
        $masala = Masala::where('status', true)->find($id);

        if (!$masala) {
            return $this->notFoundResponse('Masala not found', []);
        }

        return $this->successResponse('Masala retrieved successfully', $masala);
    }
}
