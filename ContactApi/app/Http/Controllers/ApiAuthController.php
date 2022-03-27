<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class ApiAuthController extends Controller
{
    public function register(Request $request){

        $request->validate([
            'name'=>"required|min:5|max:20",
            "email"=>"required",
            "password"=>"required|min:8|max:100",
            "password_confirmation"=>"required|same:password",
        ]);

        $user=new User();
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=Hash::make($request->password);

        $user->save();

        return $user;

    }
    public function login(Request $request){
        $credentials=$request->validate([
            "email"=>"required",
            "password"=>"required",
        ]);
        
        if(!Auth::attempt($credentials)){
            return response([
                "message"=>"Login Fail",
                "error"=>"Invalid Credentials",
            ]);
        }
        
        $token=Auth::user()->createToken("user-token");
        
        return response()->json([
            "Message"=>"Login Success",
            "Data"=>$token,
        ]);

    }

    public function logout(){
        
        Auth::user()->tokens()->delete();
        return response()->json(["message"=>"logout Successfully"],200);
    }
}
