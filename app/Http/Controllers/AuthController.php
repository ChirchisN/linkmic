<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'login' => $request->login,
            'password' => Hash::make($request->password)
        ]);

        Auth::login($user);

        return response()->json([
            'firstName' => $user->first_name,
            'lastName' => $user->last_name,
            'login' => $user->login
        ], 200);
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
            return response()->json($validator->errors(), 400);
        }

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => ['Invalid credentials!']], 401);
        }

        $user = Auth::user();

        return response()->json([
            'firstName' => $user->first_name,
            'lastName' => $user->last_name
        ]);
    }

    public function logout()
    {
        Auth::logout();

        return response()->json(['message' => 'Successful logout!']);
    }
}