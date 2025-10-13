<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Events\UserCreated;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use PhpParser\Node\Stmt\TryCatch;

use function Laravel\Prompts\error;

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
    public function login(Request $request){
        $validator = Validator::make($request->all(),[
        'email' => 'required|string|email',
        'password' => 'required|string'
      ]);
      if($validator->fails()){
        return response()->json([
          'status' => false,
          'message' => 'Validation errors',
          'errors' => $validator->errors()
        ],422);
      }
      $user = User::where('email',$request->email)->first();
      if(!$user){
        return response()->json([
            'status' => false,
            'message' => 'No user existes with that mail'
        ],401);
      }
      if(!Hash::check($request->password,$user->password)){
        return response()->json([
            'status' => false,
            'message' => 'Email/Mot de passe incorrect'
        ],401);
      }
      $token = $user->createToken('auth_token')->plainTextToken;
      return response()->json([
        'status' => true,
        'message' => 'Succesful login',
        'user' => $user,
        'token' => $token
      ]);
    }
    public function validate($id,$token){
        $user = User::findOrFail($id);
        if($user->remember_token != $token){
            return response()->view('email_verification.invalid',[
                'message' => 'Invalid verification token'
            ],400);
        }
        $user->isValidEmail = true;
        $user->remember_token = Str::random(60);
        $user->save();
        return response()->view('email_verification.success',[
            'user' => $user
        ]);

    }
    public function redirectToGoogle(){
      return Socialite::driver('google')->stateless()->redirect();
    }
    public function handleGoogleCallback(){
      try{
       $googleUser = Socialite::driver('google')->stateless()->user();
       $user = User::where('email',$googleUser->email)->first();
       if(!$user){
        $user = User::create([
          'name' => $googleUser->name,
          'email' => $googleUser->email,
          'password' => Hash::make(Str::random(16)),
          'isValidEmail' => true,
          'remember_token' => Str::random(60)
        ]);
       }
       $token = $user->createToken('google-auth')->plainTextToken;
       return redirect()->away(env('APP_URL').'/app/google?token='.$token.'&userId='.$user->id);
      }
      catch(\Exception $e){
       return redirect()->away(env('APP_URL').'/app/register?error=Unabletologintogoogle');
      }
    }
    public function getUser($id){
      try{
        $user = User::where('id',$id)->firstOrFail();
        return response()->json($user);
      }
      catch(ModelNotFoundException $e){
        echo 'User not found';
      }
    }
}
