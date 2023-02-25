<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // Register new user
    public function register(Request $request)
    {
        // Validate fields
        $attrs=$request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);

        // Create user
        $user = User::create([
            'name' => $attrs['name'],
            'email' => $attrs['email'],
            'password' => bcrypt($attrs['password'])
        ]);

        // Return user & token in repsonse
        return response(
            [
                'user' => $user,
                'token' => $user->createToken('auth_token')->plainTextToken
            ],
            200
        );
    } 

    // Login user
    public function login(Request $request)
    {
        // Validate fields
        $attrs=$request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        // Check credentials
        if (!Auth::attempt($attrs)) {
            return response(
                [
                    'message' => 'Invalid Creditentials'
                ],
                401
            );
        }

        // Return user & token in repsonse
        return response(
            [
                'user' => auth()->user(),
                'token' =>auth()->user()->createToken('auth_token')->plainTextToken
            ],
            200
        );
    }

    // Logout user
    public function logout()
    {
        // Revoke token
        auth()->user()->tokens()->delete();

        // Return message
        return response(
            [
                'message' => 'Logged out'
            ],
            200
        );
    }

    // get user details
    public function user()
    {
        return response(
            [
                'user' => auth()->user()
            ],
            200
        );
    }

}
