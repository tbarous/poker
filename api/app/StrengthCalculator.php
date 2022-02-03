<?php

namespace App;

use App\Models\Card;
use App\Models\Strength;

class StrengthCalculator
{
    public $strengths;
    public $counts = [];
    public $cards;

    public function __construct()
    {
        $this->strengths = Strength::get();
    }

    public function strength($cards)
    {
        $this->cards = $cards;

        $this->setCounts();

        if ($this->hasFlush($cards) && $this->hasRoyal()) {
            return $this->strengths->where('rank', '10')->first()->id;
        }

        if ($this->hasFlush($cards) && $this->hasStraight($cards)) {
            return $this->strengths->where('rank', '9')->first()->id;
        }

        if (array_search(4, $this->counts)) {
            return $this->strengths->where('rank', '8')->first()->id;
        }

        if (array_search(3, $this->counts) && array_search(2, $this->counts)) {
            return $this->strengths->where('rank', '7')->first()->id;
        }

        if ($this->hasFlush($cards)) {
            return $this->strengths->where('rank', '6')->first()->id;
        }

        if ($this->hasStraight($cards)) {
            return $this->strengths->where('rank', '5')->first()->id;
        }

        if (array_search(3, $this->counts)) {
            return $this->strengths->where('rank', '4')->first()->id;
        }

        if ($this->hasTwoPair($cards)) {
            return $this->strengths->where('rank', '3')->first()->id;
        }

        if (array_search(2, $this->counts)) {
            return $this->strengths->where('rank', '2')->first()->id;
        }

        return $this->strengths->where('rank', '1')->first()->id;
    }

    /**
     * @return void
     */
    public function setCounts()
    {
        foreach ($this->cards as $card) {
            $rank = Card::getRankFromString($card);

            if (array_key_exists($rank, $this->counts)) {
                $this->counts[$rank]++;
            } else {
                $this->counts[$rank] = 0;
            }
        }
    }

    /**
     * @param $cards
     * @return bool
     */
    public function hasTwoPair($cards): bool
    {
//        $array = [$cards[0]];
//
//        $pairs = [];
//
//        for ($i = 1; $i < 5; $i++) {
//            $card = $cards[$i];
//
//            if (in_array($cards[$i], $array)) {
//                $pairs[] = $cards[$i];
//            }
//
//            $array[] = $card;
//        }

        return false;
    }

    /**
     * @return bool
     */
    public function hasRoyal(): bool
    {
        $array = ['K', 'A', 'J', 'T', 'Q'];

        foreach ($this->cards as $card) {
            $rank = Card::getRankFromString($card);

            if (in_array($rank, $array)) {
                $array = array_filter($array, function ($card) use ($rank) {
                    return $card !== $rank;
                });
            } else {
                return false;
            }
        }

        return true;
    }

    private function hasStraight($ranks)
    {
//        $rank = $ranks[0];
//
//        for ($i = 1; $i < 4; $i++) {
//            $r = $ranks[$i];
//        }

        return false;
    }

    /**
     * @param $cards
     * @return bool
     */
    private function hasFlush($cards): bool
    {
        $suit = Card::getSuitFromString($cards[0]);

        for ($i = 1; $i < 4; $i++) {
            $cardSuit = Card::getSuitFromString($cards[$i]);

            if ($cardSuit !== $suit) {
                return false;
            }
        }

        return true;
    }
}
