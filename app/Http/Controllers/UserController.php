<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    //
 public function createUser(Request $request)
    {
        if(strlen($request->password)<7 )
        {
            return response() ->json(["error" => "Password too short (has to be longer than 7 characters"],201);
        }
        if(User::where('email',$request->email) ->exists())
        {
            return response() ->json(["error" => "User with this mail exists","email"=>$request->email],201);
        }
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),]);
        return response() ->json(["message" => "User has been created"],201);
    }
    public function register (Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $request['password']=Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        $user = User::create($request->toArray());
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $response = ['token' => $token];
        return response($response, 200);
    }



    public function login (Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8|confirmed',]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                $response = ['token' => $token];
                return response($response, 200);
            }
            else {
                $response = ["message" => "Password mismatch"];
                return response($response, 422);
            }
        }
        else {
            $response = ["message" =>'User does not exist'];
            return response($response, 422);
        }
    }


    public function logout (Request $request) {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }


    public function changePassword (Request $request) {
        $user = $request->user();
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|confirmed',]);
        if($request->password)
        {
            $user->password =Hash::make($request->password);
            $user->save();
            $token = $request->user()->token();
            $token->revoke();

            $response = ['message' => 'Log in again by new password'];
            return response($response, 200);
        }
        else
            {
                $response = ['message' => 'New password can not be empty!'];
                return response($response, 200);
            }
    }    
}
