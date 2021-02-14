<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\User;
use App\Role;


class UserController extends Controller
{
    public function login(Request $request){
        // echo json_encode($request->all());exit;
        $credentials = $request->only('email', 'password');

        if(Auth::attempt($credentials)){
            $token = Str::random(80);
            Auth::user()->api_token = $token;
            $permissions = Auth::user()->role->permissions;
            Auth::user()->save();
            // dd(['token'=>$token, 'permissions'=>$permissions]);
            return response()->json(['token'=>$token, 'permissions'=>$permissions], 200);
        }
        return response()->json(['status'=>'Email or Password is Wrong'], 403);
    }

    //To Varify Authentication for access apis
    public function userVarification(Request $request){
        return $request->user()->only('name', 'email');
    }

    //Auth User permissions
    public function userPermissions(Request $request){
        $getToken = $request->token;
        $user = User::where('api_token', $getToken)->first();

        $permissions = json_decode($user->role->permissions);
        // dd($permissions);
        return response()->json(['permissions'=>$permissions], 200);
    }

}
