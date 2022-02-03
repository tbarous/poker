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

        $hand = Hand::with('calculateStrength')->get();
        print_r($hand->calculateStrength());die;


        return RoundResource::collection(Round::with('hands')->get());
    }
}
