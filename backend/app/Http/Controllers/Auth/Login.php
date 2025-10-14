<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Login extends Controller
{
    //

    function login (Request $request){
        $request -> validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if(Auth::attempt(['email' => $request->email , 'password' => $request->password])){
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'message' => 'login successful',
                'user_role' => $user->role,
                'token' => $token
            ],201);
        }
        else{
            return response()->json([
                'message' => 'login failed'
            ], 401);
        }
    }

    function profile(Request $request){
        $user = $request->user();
        Log::info("user profile", ['user' => $user]);
        return response()->json([
            'user' => $user
        ], 200);
    }

  function logout(Request $request){
    $user = $request->user();

    $user->currentAccessToken()->delete();

    return response()->json([
        'message' => 'logout successful'
    ], 200);
}


}
