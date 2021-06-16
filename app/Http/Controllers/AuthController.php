<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
    	$validator = Validator::make($request->all(), [
    		'email' => 'required|unique:users',
    		'password' => 'required',
    	]);

    	if ($validator->fails()) {
    		return response()->json($validator->messages(), 200);
    	}

    	User::create([
    		'email' => $request->email,
    		'password' => bcrypt($request->password),
    	]);

    	return response([
    		'message' => 'User successfully registered'
		]);
    }

    public function login(Request $request)
    {
    	if (!Auth::attempt($request->only('email', 'password'))) {
            return response([
                'message' => 'Invalid credentials!' 
            ], 401);
        }

        $token = Auth::user()->createToken('token')->plainTextToken;

        return response(['token' => $token]);
    }
}
