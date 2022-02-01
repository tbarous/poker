<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $data['password'] = password_hash($request->password, PASSWORD_DEFAULT);

        $player = Player::create($data);

        $token = $player->createToken('API Token')->accessToken;

        return response([
            'player' => $player,
            'token' => $token
        ]);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        if (!auth()->attempt($data)) {
            return response([
                'error_message' => 'Incorrect Details. Please try again'
            ]);
        }

        $player = auth()->user();

        $token = $player->createToken('API Token')->accessToken;

        return response([
            'player' => auth()->user(),
            'token' => $token
        ]);
    }
}
