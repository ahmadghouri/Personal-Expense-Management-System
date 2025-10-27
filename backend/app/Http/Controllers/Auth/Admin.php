<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class Admin extends Controller
{
    //
    public function index()
    {
        $user = User::all();

        return response()->json($user);
    }

    public function updata(Request $request, $id)
    {
        $user = User::find($id);
        if (! $user) {
            return response()->json(['message' => 'user not found'], 404);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        // $user->password = $request->password;

        if ($user->save()) {
            return response()->json([
                'message' => 'user updated successfully',
                'user' => $user,
            ], 201);
        }

        return response()->json(['message' => 'Failed to update user'], 500);
    }

    public function delete($id)
    {
        $user = User::find($id);
        if (! $user) {
            return response()->json(['message' => 'user not found'], 404);
        }

        if ($user->delete()) {
            return response()->json([
                'message' => 'user deleted successfully',
                'user' => $user,
            ], 201);
        }

        return response()->json(['message' => 'Failed to delete user'], 500);
    }
}
