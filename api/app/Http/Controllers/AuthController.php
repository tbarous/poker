<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class AuthController extends Controller
{
    const TOKEN_NAME = 'API Access';

    /**
     * @param RegisterRequest $request
     * @return mixed
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $data['password'] = Hash::make($request->password);

        $player = Player::create($data);

        return response(['player' => $player, 'token' => $this->token($player)], 200);
    }

    /**
     * @param LoginRequest $request
     * @return mixed
     */
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $valid = auth()->attempt($data);

        if (!$valid) {
            return response(['error_message' => 'Incorrect Credentials. Please try again']);
        }

        $player = auth()->user();

        $data = [
            'player' => $player,
            'token' => $this->token($player)
        ];

        return response($data, 200);
    }

    /**
     * @param Player $player
     * @return string
     */
    private function token(Player $player): string
    {
        $player->tokens()->delete();

        return $player->createToken(self::TOKEN_NAME)->plainTextToken;
    }
}
