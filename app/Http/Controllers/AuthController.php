<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
    	// $this->validate($request, [
    	// 	'email' => 'required|unique:users',
    	// 	'password' => 'required',
    	// ]);
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
}
