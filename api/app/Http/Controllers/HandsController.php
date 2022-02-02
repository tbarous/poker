<?php

namespace App\Http\Controllers;

use App\Jobs\UploadHands;
use App\Models\Hand;
use App\Models\Player;
use App\Models\Round;
use File;
use Illuminate\Support\Facades\Request;
use Queue;
use Faker\Factory as Faker;

class HandsController extends Controller
{
    public function upload()
    {
        $file = request()->hands;

        $content = File::get($file);

        $faker = Faker::create();

        $handsData = [];
        $roundsData = [];

        $latestRound = Round::latest();

        if (empty($latestRoundId)) {
            $latestRoundId = 1;
        } else {
            $latestRoundId = $latestRound->id;
        }

        $hands = explode(PHP_EOL, $content);

        $player1 = Player::create([
            'name' => 'Player 1',
            'email' => $faker->email,
            'password' => '123456'
        ]);

        $player2 = Player::create([
            'name' => 'Player 2',
            'email' => $faker->email,
            'password' => '123456'
        ]);

        foreach ($hands as $hand) {
            if (empty($hand)) continue;

            $cards = explode(' ', $hand);

            $player1Hand = array_slice($cards, 0, 5);

            $player2Hand = array_slice($cards, 4, 5);

            $roundsData[] = [];

            $handsData[] = [
                'card_1' => $player1Hand[0],
                'card_2' => $player1Hand[1],
                'card_3' => $player1Hand[2],
                'card_4' => $player1Hand[3],
                'card_5' => $player1Hand[4],
                'round_id' => $latestRoundId,
                'player_id' => $player1->id
            ];

            $handsData[] = [
                'card_1' => $player2Hand[0],
                'card_2' => $player2Hand[1],
                'card_3' => $player2Hand[2],
                'card_4' => $player2Hand[3],
                'card_5' => $player2Hand[4],
                'round_id' => $latestRoundId,
                'player_id' => $player2->id
            ];

            $latestRoundId++;
        }
        Round::insert($roundsData);

        Hand::insert($handsData);


//        Queue::push(new UploadHands($file));
    }
}
