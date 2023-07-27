<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showRegistration()
    {
        return view('registration');
    }

    public function registration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstName' => ['required', 'min:3', 'max:25'],
            'lastName' => ['required', 'min:3', 'max:25'],
            'login' => ['required', 'min:3', 'max:25', 'unique:users'],
            'password' => ['required', 'min:6', 'confirmed'],
            'password_confirmation' => ['required', 'min:6']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $user = User::create([
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'login' => $request->login,
            'password' => Hash::make($request->password)
        ]);

        Auth::login($user);

        return new UserResource($user);
    }

    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = [
            'login' => $request->login,
            'password' => $request->password
        ];

        $validator = Validator::make($credentials, [
            'login' => ['required'],
            'password' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => ['Invalid credentials!']], Response::HTTP_UNAUTHORIZED);
        }

        return new UserResource(Auth::user());
    }

    public function logout()
    {
        Auth::logout();

        return response()->json(['message' => 'Successful logout!'], Response::HTTP_OK);
    }
}