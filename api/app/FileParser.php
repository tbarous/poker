<?php

namespace App;

use App\Jobs\UploadHands;
use App\Models\Card;
use App\Models\Hand;
use App\Models\Player;
use App\Models\Round;
use Error;
use Exception;
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
     * @throws Exception
     */
    public function parse($file)
    {
        $this->checkFileSize($file);

        // Get the contents of the file in string format.
        $content = File::get($file);

        // The final array with all the hands that will
        // be inserted into the *hands* table.
        $allHands = [];

        $playersIds = $this->createPlayers(8);

        // Set up the cards deck.
        $deck = Card::get();

        // Init a hand strength calculator instance to be used
        // when calculating hand strengths.
        $strengthCalculator = new StrengthCalculator();

        // Transform the file content splitting it in
        // newlines (/n) and storing it in the rounds array.
        $rounds = array_filter(explode(PHP_EOL, $content));

        // Loop each round. Each round should have the form
        // '3D 7D QC KH JH 6D 6C TD TH KD'.
        foreach ($rounds as $roundKey => $round) {

            // Get hands array in the form ['3D 7D QC KH JH', '6D 6C TD TH KD'].
            $hands = $this->getHands($round, $roundKey);

            // The array where we store all the hands for the current round.
            $roundHandsResult = [];

            // Loop each hand of the round. Each hand should have the form '3D 7D QC KH JH'
            foreach ($hands as $handKey => $hand) {

                // Get cards array in the form ['3D', '7D', 'QC', 'KH', 'JH'].
                $cards = $this->getCards($hand, $roundKey);

                // Initialize the current hand's data that will be inserted into the hands table.
                $handData = [
                    'player_id' => $playersIds[$handKey],
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString()
                ];

                // Get the hands cards in a laravel collection form and also get the
                // cards ids to be inserted into db with hand data above.
                [$collectionCards, $handCards] = $this->getCollectionCards($cards, $deck, $roundKey);

                // Merge hand data with hand card ids.
                $handToInsert = array_merge($handData, $handCards);

                // Set up hand strength id.
                $handToInsert['strength_id'] = $strengthCalculator->getStrength($collectionCards)->id;

                // Add the current hand to the round hands array.
                $roundHandsResult[] = $handToInsert;
            }

            // Create a round id to be used with hands insertion.
            $roundId = Round::create()->id;

            // For the current round, each hand should have an associated round_id.
            foreach ($roundHandsResult as $h) {
                $h['round_id'] = $roundId;

                $allHands[] = $h;
            }
        }

        // Insert all hands into hands table.
        Hand::insert($allHands);
    }

    /**
     * Check the received file's size.
     *
     * @return void
     * @throws Exception
     */
    private function checkFileSize($file)
    {
        $size = File::size($file);

        if ($size > 1000000) {
            $this->error("File size is too large.");
        }
    }

    /**
     * Creates dummy players and store their IDs in array.
     *
     * @param int $howMany
     * @return array
     */
    private function createPlayers(int $howMany): array
    {
        $playersIds = [];

        for ($i = 0; $i < $howMany; $i++) {
            $playersIds[] = Player::factory()->create()->id;
        }

        return $playersIds;
    }

    /**
     * Gets cards in the form of an array with 5 items.
     * Parses every card, validates them and returns them
     * as a 5 card collection.
     * @param $cards
     * @param $cardsCollection
     * @param $roundKey
     * @return array
     * @throws Exception
     */
    private function getCollectionCards($cards, $cardsCollection, $roundKey): array
    {
        $collectionCards = [];
        $handCards = [];

        foreach ($cards as $cardKey => $card) {
            $split = str_split($card);

            if (empty($split) || count($split) !== 2) {
                $this->error('Unknown card type on round: ' . ($roundKey + 1));
            }

            $suit = $split[1];
            $rank = $split[0];

            $collectionCard = $cardsCollection->where('suit', $suit)->where('rank', $rank)->first();

            if ($collectionCard) {
                $handCards[Hand::CARDS_ID[$cardKey]] = $collectionCard->id;

                $collectionCards[] = $collectionCard;
            } else {
                $this->error('Unknown card type on round: ' . ($roundKey + 1));
            }
        }

        return [$collectionCards, $handCards];
    }

    /**
     * Throws error.
     * @param string $message
     * @return mixed
     * @throws Exception
     */
    private function error(string $message)
    {
        throw new Exception($message);
    }

    /**
     * Gets a hand's cards.
     * @param $hand
     * @param $roundIndex
     * @return false|string[]
     * @throws Exception
     */
    private function getCards($hand, $roundIndex)
    {
        $cards = explode(" ", $hand);

        // If cards array is empty or less than 5 throw error
        if (empty($cards) || count($cards) !== 5) {
            $this->error('Cards at round ' . ($roundIndex + 1) . 'are malformed');
        };

        return $cards;
    }

    /**
     * If hands array is empty or is not divisible by 5 throw an error.
     * @param $round
     * @param $index
     * @return array
     * @throws Exception
     */
    private function getHands($round, $index): array
    {
        $hands = [];
        $hand = [];
        $cards = explode(" ", $round);

        foreach ($cards as $card) {
            $hand[] = $card;

            if (count($hand) === 5) {
                $hands[] = implode(" ", $hand);

                $hand = [];
            }
        }

        if (empty($hands)) {
            $this->error('Round ' . ($index + 1) . ' is malformed');
        };

        return $hands;
    }
}
