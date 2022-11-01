<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller

{
    use ApiResponser;

    public function login(Request $request)
    {
        $attr = $request->validate([
            'username' => 'required|string|min:3|max:150|unique:users,username',
            'password' => 'required|string|confirmed',
        ]);
        $user = User::where('username', $request->username)->first();
        if (!Auth::attempt($attr)) {
            return $this->error('Credentials not match', 401);
        }
        if (!$user) {
            return response()->json(["message" => "username errado"]);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(["message" => "username errado"]);
        }

        $token = $user->createToken($request->username . strtotime("now"))->plainTextToken;
        return response()->json([
            "acess_token" => $token,
        ]);
    }

    
    public function register(Request $request)
    {
        $attr = $request->validate([

            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $user = User::create([
            'password' => bcrypt($attr['password']),
            'username' => $attr['username']
        ]);

        return $this->success([
            'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }

}
