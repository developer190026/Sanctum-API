<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function signup(Request $request){
        $ValidateUser = Validator::make($request->all(),
        [
            "name" => "required",
            "email" => "required|email|unique:users,email",
            "password" => "required"
        ]);
        
    if($ValidateUser->fails()){
       return response()->json([
        "status" => false,
        "message" => "Validation error",
        "errors" => $ValidateUser->errors()->all()
       ],401);
    }
    else{
        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => $request->password

        ]);
        return response()->json([
            "status" => true,
            "message" => "user created succesfully",
            "user" => $user
           ],200);

    }
   }

    public function login(Request $request){
        

        $ValidateUser = Validator::make($request->all(),
        [
           // "name" => "required",
            "email" => "required|email",
            "password" => "required"
        ]);

        if($ValidateUser->fails()){
            return response()->json([
             "status" => false,
             "message" => "Authentication error",
             "errors" =>$ValidateUser->errors()->all()
            ],404);
         }
        if(Auth::attempt(["email" => $request->email,"password" => $request->password])){
            $authUser=Auth::user();
           // $request->session()->regenerate();
            return response()->json([
            "status" => true,
            "message" => "user loggedin succesfully",
            "token" => $authUser->createToken("API token")->plainTextToken,
            "token_type" => "bearer",
           // "user" => $authUser
            ],200);
        }
        else{
            return response()->json([
                "status"=> false,
                "message"=> "Email and password does not matched"
            ],401);
        }
        
    }
    public function logout(Request $request)
    {
        $user = $request->user();
    
        if (!$user) {
            return response()->json([
                "status" => false,
                "user" => $user,
                "message" => "Unauthorized or token missing"
            ], 401);
        }
    
        $user->tokens()->delete();
    
        return response()->json([
            "status" => true,
            "user" => $user,
            "message" => "User logged out successfully"
        ]);
    }
    
    
}
