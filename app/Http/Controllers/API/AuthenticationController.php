<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; 

class AuthenticationController extends Controller
{
	// User Registration
    public function register(Request $request)
    {
    	$request->validate([ 
            'first_name' => 'required', 
            'middle_name' => 'required', 
            'last_name' => 'required', 
            'email' => 'required|max:191|unique:users,email', 
            'password' => 'required', 
            'c_password' => 'required|same:password', 
        ]);

        $user= User::create([
        	'first_name' => $request->first_name, 
            'middle_name' => $request->middle_name, 
            'last_name' => $request->last_name, 
            'email' => $request->email, 
            'password' => Hash::make($request->password), 
        ]);

        $token = $user->createToken('myToken')->plainTextToken;

        return response([
        	'user' =>$user,
        	'token'=>$token,
        ]);

    }

    public function login(Request $request)
    {
    	$request->validate([ 
            'email' => 'required|email', 
            'password' => 'required', 
        ]);

        $user = User::where('email',$request->email)->first();

        if(!$user || !Hash::check($request->password,$user->password))
        {
        	return response([
        		'message'=> 'The provided credentials are incorrect'],401);
        }
        else
        {
        	 $token = $user->createToken('myToken')->plainTextToken;
        	return response([
        	'user' =>$user,
        	'token'=>$token,
       	 ]);
        }

        
    }

    // User Logout
    public function logout()
    {
    	auth()->user()->tokens()->delete();

    	return response([
    		'message' => 'Logout Successfully....']);
    }

}
