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
        return RoundResource::collection(Round::with(
            'hands.strength',
            'hands.firstCard',
            'hands.secondCard',
            'hands.thirdCard',
            'hands.fourthCard',
            'hands.fifthCard',
            'hands.winner'
        )->get());
    }
}
