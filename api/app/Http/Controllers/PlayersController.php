<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlayerStoreRequest;
use App\Http\Resources\PlayerResource;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PlayersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $players = Player::all();

        return response([
            'players' => PlayerResource::collection($players),
            'message' => 'Success'
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PlayerStoreRequest $request
     * @return Response
     */
    public function store(PlayerStoreRequest $request): Response
    {
        $data = $request->validated();

        $player = Player::create($data);

        $collection = new PlayerResource($player);

        $data = ['players' => $collection, 'message' => 'Success'];

        return response($data, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param Player $player
     * @return Response
     */
    public function show(Player $player): Response
    {
        $player = new PlayerResource($player);

        $data = [
            'player' => $player,
            'message' => 'Success'
        ];

        return response($data, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Player $player
     * @return Response
     */
    public function update(Request $request, Player $player): Response
    {
        $player->update($request->all());

        return response(['employee' => new PlayerResource($player), 'message' => 'Success'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Player $player
     * @return Response
     */
    public function destroy(Player $player): Response
    {
        $player->delete();

        return response(['message' => 'Employee deleted']);
    }
}
