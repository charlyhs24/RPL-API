<?php

namespace App\Http\Controllers\auth;
use JWTAuth;
use App\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class authController extends Controller
{
    //
    public function signup(Request $request){
        // dd("ah");
        $this->validate($request,[
            'username' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
        ]);
        return User::create([
            'username' => $request->json('username'),
            'email' => $request->json('email'),
            'password' => bcrypt($request->json('password')),
        ]);
        // return response('registrasi akun berhasil');
    }
    public function signin(Request $request){
        // grab credentials from the request
        $credentials = $request->only('username', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        // all good so return the token
        return response()->json([
            'user_id'=> $request->user()->id,
            'username'=> $request->user()->username,
            'email'=> $request->user()->email,
            'token' => $token
        ]);
 
    }
}
