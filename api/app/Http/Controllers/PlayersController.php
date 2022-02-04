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
     * @return Response
     */
    public function index()
    {
        $players = Player::all();

        $data = [
            'players' => PlayerResource::collection($players),
            'message' => 'Success'
        ];

        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     * @param PlayerStoreRequest $request
     * @return Response
     */
    public function store(PlayerStoreRequest $request)
    {
        $data = $request->validated();

        $player = Player::create($data);

        $collection = new PlayerResource($player);

        $data = ['players' => $collection, 'message' => 'Success'];

        return response()->json($data, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param Player $player
     * @return Response
     */
    public function show(Player $player)
    {
        $player = new PlayerResource($player);

        $data = [
            'player' => $player,
            'message' => 'Success'
        ];

        return response()->json($data, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Player $player
     * @return Response
     */
    public function update(Request $request, Player $player)
    {
        $player->update($request->all());

        $data = ['employee' => new PlayerResource($player), 'message' => 'Success'];

        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Player $player
     * @return Response
     */
    public function destroy(Player $player)
    {
        $player->delete();

        $data = ['message' => 'Player deleted'];

        return response()->json($data, 200);
    }
}
