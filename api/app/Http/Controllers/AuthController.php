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

    private function token(Player $player)
    {
        return $player->createToken(self::TOKEN_NAME)->plainTextToken;
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
            return response(['error_message' => 'Incorrect Details. Please try again']);
        }

        $player = auth()->user();

        return response(['player' => auth()->user(), 'token' => $this->token($player)]);
    }
}
