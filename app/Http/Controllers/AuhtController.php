<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Events\UserCreated;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuhtController extends Controller
{
    public function generateRandomCode(){
      return Str::random(10).time();
    }
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
        'name' => 'required|string|max:255',
        'email' =>'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed'
       ]);
       if($validator->fails()){
        return response()->json([
            'status' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors()
        ],422);
       };
       $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'isValidEmail' => false,
        'remember_token' => $this->generateRandomCode()
       ]);
       event(new UserCreated($user));
       return response()->json([
        'status' => true,
        'message' => 'User created succesfully',
        'user' => $user
       ]);
    }
}
