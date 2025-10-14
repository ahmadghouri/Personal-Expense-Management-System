<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class Register extends Controller
{
    //
        function register (Request $request){
        $request->validate([
            'name' => 'required|string',
            'email' => "required|email|unique:users,email",
            'password' => "required|string|min:8|max:20",
        ]);

        $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = $request->password;
     

        if($user->save()){
            return response()->json([
                'message' => 'manager registered successfully',
                'user' => $user,
            ],201);
        }
        return response()->json(['message' => 'Failed to register user'], 500);
    }
}
