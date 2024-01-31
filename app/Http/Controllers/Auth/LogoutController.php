<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper\ResponseHelper;
use Auth;
use Illuminate\Http\JsonResponse;

class LogoutController extends Controller
{
    private $helping;
    public function __construct(){
        $this->helping = new ResponseHelper();
    }

    public function logout(Request $request)
    {
        $loggedInChecked = Auth::check();
        if(! $loggedInChecked){
            return  response( [
                'msg' => 'Not allowed',
            ], 401 );
        }
        
        // return Auth::getConfig('api');
        $request->user()->token()->revoke();
        $responseData = $this->helping->logout();
        return response()->json($responseData);
    }
}