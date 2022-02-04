<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoundResource;
use App\Models\Hand;
use App\Models\Round;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function index()
    {
        $rounds = Round::select(['id'])->with(
            'hands',
            'hands.firstCard:id,rank,suit',
            'hands.strength:id,name',
            'hands.secondCard:id,rank,suit',
            'hands.thirdCard:id,rank,suit',
            'hands.fourthCard:id,rank,suit',
            'hands.fifthCard:id,rank,suit'
        )->get();

        return response()->json(['rounds' => $rounds], 200);
    }
}
