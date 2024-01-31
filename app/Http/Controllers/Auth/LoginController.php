<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Helper\ResponseHelper;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Auth;

class LoginController extends Controller
{
    public function __construct(){
        $this->helping = new ResponseHelper();
    }

    public function login( Request $request )
    {

        $data = $request->validate( [
            'email' => 'required|string',
            'password' => 'required|string',
        ] );

        $user = User::where( 'email', $data['email'] )->first();

        if (! Auth::attempt( ['email' => request( 'email' ), 'password' => request( 'password' )] ) ) {

            return response( [
                'msg' => 'incorrect username or password',
            ], 401 );
        }



        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if($validator->fails()){
            $errors = $validator->errors();
            $errorMsg = "";
            foreach ($errors->all() as $msg) {
                $errorMsg .= $msg;
            }
            $responseData = $this->helping->validatingErrors($errorMsg);
            return response()->json($responseData);
        }

        $user = User::where('email', $request->email)->first();

        if(! $user){
            $responseData = $this->helping->invalidLogin("User does not exist. Please Sign Up.");
            return response()->json($responseData);
        }

        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){

            $user = Auth::user();

            
            $user['api_token'] = $user->createToken('auth_token')->plainTextToken;

            $user['token_type'] = "Bearer";

            $responseData = $this->helping->loggedIn([
                'users' => $user
            ]);

            return response()->json($responseData);
        }
        else{
            $responseData = $this->helping->invalidLogin("Incorrect Password.");
            return response()->json($responseData);
        }

    }

}
