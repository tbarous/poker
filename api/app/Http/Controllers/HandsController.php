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
    /**
     * @return void
     */
    public function upload()
    {
        $handsToInsert = [];
        $roundsToInsert = [];

        $playersIds = [];

        for ($i = 0; $i < 9; $i++) {
            $playersIds[] = Player::factory()->create()->id;
        }

        $cardsCollection = Card::get();
        $lastRoundId = Round::getLastId();

        $rounds = $this->getRounds();

        foreach ($rounds as $roundKey => $round) {
            $hands = $this->getHands($round);

            if (empty($hands)) continue;

            foreach ($hands as $handKey => $hand) {
                $cards = $this->getCards($hand);

                if (empty($cards)) continue;

                $handToInsert = [
                    'player_id' => $playersIds[$handKey],
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                    'round_id' => $lastRoundId
                ];

                foreach ($cards as $cardKey => $card) {
                    $suit = $this->getSuit($card);
                    $rank = $this->getRank($card);

                    $collectionCard = $cardsCollection->where("suit", $suit)->where("rank", $rank)->first();

                    if ($collectionCard) {
                        $handToInsert[Hand::CARDS_ID[$cardKey]] = $collectionCard->id;
                    }
                }

                $handsToInsert[] = $handToInsert;
            }

            $roundsToInsert[] = $this->getRoundToInsert();

            $lastRoundId++;
        }

        Round::insert($roundsToInsert);
        Hand::insert($handsToInsert);
    }

    /**
     * @return array
     */
    private function getRoundToInsert(): array
    {
        return ['created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()];
    }

    /**
     * @return false|string[]
     */
    private function getRounds()
    {
        $file = request()->hands;

        $size = File::size($file);

        if ($size > 100000) return [];

        $content = File::get($file);

        return explode(PHP_EOL, $content);
    }

    /**
     * @param $card
     * @return mixed|string
     */
    private function getSuit($card)
    {
        $split = str_split($card);

        return $split[1];
    }

    /**
     * @param $card
     * @return mixed|string
     */
    private function getRank($card)
    {
        $split = str_split($card);

        return $split[0];
    }

    /**
     * @param $hand
     * @return false|string[]
     */
    private function getCards($hand)
    {
        return explode(" ", $hand);
    }

    /**
     * @param $round
     * @return array
     */
    private function getHands($round): array
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
