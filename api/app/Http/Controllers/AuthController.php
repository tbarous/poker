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
     * Registers a user.
     * @param RegisterRequest $request
     * @return mixed
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $data['password'] = Hash::make($request->password);

        $player = Player::create($data);

        $data = ['player' => $player, 'token' => $this->token($player)];

        return response()->json($data, 200);
    }

    /**
     * Logins a user.
     * @param LoginRequest $request
     * @return mixed
     */
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $valid = auth()->attempt($data);

        if (!$valid) {
            $data = ['error' => 'Incorrect Credentials. Please try again'];

            return response()->json($data, 422);
        }

        $player = auth()->user();

        $data = [
            'player' => $player,
            'token' => $this->token($player)
        ];

        return response()->json($data, 200);
    }

    /**
     * Creates a unique token.
     * @param Player $player
     * @return string
     */
    private function token(Player $player): string
    {
        $player->tokens()->delete();

        return $player->createToken(self::TOKEN_NAME)->plainTextToken;
    }
}
