<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index($token)
    {
        $user = User::where('remember_token', $token)->first();
        if(!$user) return response()->json('Not found', 404);
        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];

        return response()->json($data, 200);
    }
}