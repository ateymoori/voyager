<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request){
        $validatedData = $request->validate(
            [
                "name" => "required|max:55" ,
                "country" => "max:55" ,
                "city" => "max:55" ,
                "os" => "max:55" ,
                "os_version" => "max:55" ,
                "app_version" => "max:55" ,
                "email" => "email|required|unique:users" ,
                "password" => "required|max:55"
            ]
        );

        $validatedData['password'] = bcrypt($request->password)  ;
        $user = User::create($validatedData) ;
        $accessToken = $user->createToken('authToken')->accessToken ;
        return response(['user'=> $user , 'access_token' => $accessToken]) ;
    }

    public function login(Request $request){
        $validatedData = $request->validate(
            [
                "email" => "email|required" ,
                "password" => "required"
            ]
        );

        if( !auth()->attempt($validatedData)){
            return response(["message" => "Error in credential"]) ;
        }else{
            $accessToken = auth()->user()->createToken('authToken')->accessToken ;
            return response(['user'=> auth()->user() , 'access_token' => $accessToken]) ;
        }

    }
}
