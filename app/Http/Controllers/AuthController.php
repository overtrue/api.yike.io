<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['register']);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'username' => $request->get('username'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
        ]);

        $user->sendActiveMail();

        $token = $user->createToken('Laravel Password Grant Client')->accessToken;

        return response()->json([
            'token' => $token
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|hash:' . auth()->user()->password,
            'password' => 'required|different:old_password|confirmed',
        ], [
            'old_password.hash' => '旧密码输入错误！'
        ], [
            'old_password' => '旧密码'
        ]);

        auth()->user()->update([
            'password' => bcrypt($request->get('password'))
        ]);

        return response()->json([]);
    }
}
