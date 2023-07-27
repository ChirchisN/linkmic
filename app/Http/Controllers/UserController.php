<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function user()
    {
        if (!Auth::check()) {
            return response()->json(['message' => "User is not logged!"], Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();

        return new UserResource($user);
    }
}
