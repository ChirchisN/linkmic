<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function user()
    {
        if (!Auth::check()) {
            return response()->json(['message' => "User is not logged!"], 401);
        }

        $user = Auth::user();
        return response()->json([
            'firstName' => $user->first_name,
            'lastName' => $user->last_name
        ]);
    }
}
