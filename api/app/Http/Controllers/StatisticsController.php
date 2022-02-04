<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoundResource;
use App\Models\Hand;
use App\Models\Round;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
        $rounds = Round::select(['id'])->with(
            'hands.firstCard:id,rank,suit',
            'hands.strength:id,name',
            'hands.secondCard:id,rank,suit',
            'hands.thirdCard:id,rank,suit',
            'hands.fourthCard:id,rank,suit',
            'hands.fifthCard:id,rank,suit'
        )->get();

        return response()->json(['rounds' => $rounds], 200);
    }

    /**
     * @return mixed
     */
    public function bestHands()
    {
        $hands = Hand::whereHas('strength', function ($query) {
            $query->where('rank', '>', '5');
        })->with('strength:id,name')->get();

        return response()->json(['hands' => $hands], 200);
    }
}
