<?php

namespace App\Http\Controllers;

use App\Jobs\UploadHands;
use App\Models\Hand;
use App\Models\Player;
use App\Models\Round;
use File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Queue;
use Faker\Factory as Faker;
use \Carbon\Carbon;

class HandsController extends Controller
{
    const RANKS = [
        '1' => 1,
        '2' => 2,
        '3' => 3,
        '4' => 4,
        '5' => 5,
        '6' => 6,
        '7' => 7,
        '8' => 8,
        '9' => 9,
        '10' => 10,
        'J' => 11,
        'Q' => 12,
        'K' => 13,
        'A' => [1, 14]
    ];

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
            'name' => $faker->name,
            'email' => $faker->email,
            'password' => Hash::make($faker->password),
        ]);

        $player2 = Player::create([
            'name' => $faker->name,
            'email' => $faker->email,
            'password' => Hash::make($faker->password),
        ]);

        foreach ($hands as $hand) {
            if (self::isValidHand($hand)) {
                $cards = explode(' ', $hand);

                $player1Hand = implode(' ', array_slice($cards, 0, 5));
                $player2Hand = implode(' ', array_slice($cards, 4, 5));

                $roundsData[] = ['created_at' => $this->now(), 'updated_at' => $this->now(),];

                $handsData[] = $this->getHandData($player1Hand, $latestRoundId, $player1->id);

                $handsData[] = $this->getHandData($player2Hand, $latestRoundId, $player2->id);

                $latestRoundId++;
            }
        }

        Round::insert($roundsData);

        Hand::insert($handsData);
    }

    /**
     * @param $hand
     * @return bool
     */
    private static function isValidHand($hand): bool
    {
        $cards = explode("", $hand);

        if (count($cards) !== 5) {
            return false;
        }

        foreach ($cards as $card) {
            $cardSplit =
        }
    }

    private function now()
    {
        return Carbon::now()->toDateTimeString();
    }

    private function getHandStrength($cards)
    {
        $strength = 10;

        $suits = [];
        $ranks = [];

        foreach ($cards as $card) {
            $cardSplit = explode($card, "");

            $ranks[] = $cardSplit[0];
            $suits[] = $cardSplit[1];
        }

        $flush = $this->flush($suits);

        return $strength;
    }

    function straight($ranks)
    {
        $rank = $ranks[0];

        for ($i = 1; $i < 4; $i++) {
            $r = $ranks[$i];
        }
    }

    /**
     * @param $suits
     * @return bool
     */
    private function flush($suits): bool
    {
        $suit = $suits[0];

        for ($i = 1; $i < 4; $i++) {
            if ($suits[$i] !== $suit) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $hand
     * @param $roundId
     * @param $playerId
     * @return array
     */
    private function getHandData($hand, $roundId, $playerId): array
    {
        return [
            'cards' => $hand,
            'round_id' => $roundId,
            'player_id' => $playerId,
            'created_at' => $this->now(),
            'updated_at' => $this->now(),
            'strength' => $this->getHandStrength()
        ];
    }
}
