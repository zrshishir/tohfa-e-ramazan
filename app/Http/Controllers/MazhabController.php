<?php

namespace App\Http\Controllers;

use App\Traits\HelperTrait;
use App\Models\Mazhab;
use Illuminate\Http\Request;

class MazhabController extends Controller
{
    use HelperTrait;

    public function index()
    {
        $mazhabs = Mazhab::all();

        return $this->jsonResponse('success', 200, 'Mazhab Data', $mazhabs);
    }
}