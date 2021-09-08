<?php

namespace App\Http\Controllers;

use App\Models\User;
use JWTAuth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

class APIController extends Controller
{
    public function register(Request $request){

        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json([

            "success" => true,
            "data"=> $user
        ],200);


    }

    public function login(Request $request){

        $input =$request->only('email','password');
        $token =null;
      // dd($token = JWTAuth::attempt($input));
        if(!$token = JWTAuth::attempt($input)){
            return response()->json([
                "success"=>false,
                "message"=>"invalid email or password"
            ],401);
        }
        return response()->json([

            "success"=>true,
            "token"=>$token
        ]);

    }

    public function logout(Request $request){
        //dd($request);
        $this->validate($request,[
            'token'=>'required'
        ]);

        
        try{
            JWTAuth::invalidate($request->token);
            return response()->json([

                "success"=>true,
                "message"=>"user logged out"
            ]);
        }catch(JWTException $e){

            return response()->json([

                "success"=>false,
                "message"=>$e
            ]);
        }
        
    }
}