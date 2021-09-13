<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserToken;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('username', $request->username)
            ->where('password', $request->password)
            ->first();
        if (!$user) {
            return response([
                'message' => 'invalid login'
            ], 401);
        }
        $token = UserToken::firstOrCreate([
            'user_id' => $user->id,
            'token' => md5($user->username)
        ]);

        return response([
            'token' => $token->token,
            'role' => $user->role
        ], 200);
    }

    public function logout(Request $request)
    {
        $userToken = UserToken::where('token', $request->token)->first();

        if ($userToken == null) {
            return response([
                'message' => 'Unauthorized user'
            ], 401);
        }

        $userToken->delete();

        return response([
            'message' => 'logout success'
        ], 200);
    }
}
