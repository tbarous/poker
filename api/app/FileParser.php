<?php

namespace App;

use App\Jobs\UploadHands;
use App\Models\Card;
use App\Models\Hand;
use App\Models\Player;
use App\Models\Round;
use Error;
use File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Queue;
use Faker\Factory as Faker;
use \Carbon\Carbon;

class FileParser
{
    /**
     * @return void
     */
    public function parse($file)
    {
        $size = File::size($file);

        if ($size > 100000) {
            self::error("File size is too large.");
        };

        $strengthCalculator = new StrengthCalculator();

        $content = File::get($file);

        $allHandsToInsert = [];

        $playersIds = [];

        for ($i = 0; $i < 9; $i++) {
            $playersIds[] = Player::factory()->create()->id;
        }

        $cardsCollection = Card::get();

        $rounds = explode(PHP_EOL, $content);

        foreach ($rounds as $roundKey => $round) {
            $hands = self::getHands($round);

            $handsToInsert = [];

            if (empty($hands)) continue;

            foreach ($hands as $handKey => $hand) {
                $cards = self::getCards($hand);

                if (empty($cards)) continue;

                $handToInsert = [
                    'player_id' => $playersIds[$handKey],
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString()
                ];

                $collectionCards = [];

                foreach ($cards as $cardKey => $card) {
                    $suit = Card::getSuitFromString($card);
                    $rank = Card::getRankFromString($card);

                    $collectionCard = $cardsCollection->where("suit", $suit)->where("rank", $rank)->first();

                    if ($collectionCard) {
                        $handToInsert[Hand::CARDS_ID[$cardKey]] = $collectionCard->id;
                        $collectionCards[] = $collectionCard;
                    }
                }

                $id = $strengthCalculator->strength($collectionCards);

                $handToInsert['strength_id'] = $id;

                $handsToInsert[] = $handToInsert;
            }

            $roundId = Round::create()->id;

            foreach ($handsToInsert as $h) {
                $h['round_id'] = $roundId;

                $allHandsToInsert[] = $h;
            }
        }

        Hand::insert($allHandsToInsert);
    }

    private function error(string $message)
    {
        throw new Error($message);
    }

    /**
     * @param $hand
     * @return false|string[]
     */
    private static function getCards($hand)
    {
        return explode(" ", $hand);
    }

    /**
     * @param $round
     * @return array
     */
    private static function getHands($round): array
    {
        $cards = explode(" ", $round);

        $hands = [];

        $hand = [];

        foreach ($cards as $card) {
            $hand[] = $card;

            if (count($hand) === 5) {
                $hands[] = implode(" ", $hand);

                $hand = [];
            }
        }

        return $hands;
    }
}
