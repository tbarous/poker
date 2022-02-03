<?php

namespace App\Http\Controllers;

use App\Jobs\UploadHands;
use App\Models\Card;
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
    private function isValidFile($file)
    {
 
    }

    public function upload()
    {
        $file = request()->hands;

        $content = File::get($file);

        $faker = Faker::create();

        $handsData = [];
        $roundsData = [];

        $cards = Card::get();

        dd($cards);

        $hands = explode(PHP_EOL, $content);

        $player1 = Player::factory()->make();

        $player2 = Player::factory()->make();

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
//            $cardSplit =
        }
    }

    private function now()
    {
        return Carbon::now()->toDateTimeString();
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
