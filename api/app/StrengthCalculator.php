<?php

namespace App;

use App\Models\Card;
use App\Models\Strength;

class StrengthCalculator
{
    public $strengths;

    const MAPPING = ['A' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9, 'T' => 10, 'J' => 11, 'Q' => 12, 'K' => 13];

    /**
     * Setup strengths to be available throughout the instance's
     * life.
     */
    public function __construct()
    {
        $this->strengths = Strength::get();
    }

    /**
     * @param $cards
     * @return mixed
     */
    public function getStrength($cards)
    {
        $counts = $this->getCounts($cards);

        // ROYAL FLUSH
        if ($this->hasFlush($cards) && $this->hasRoyal($cards)) {
            return $this->strengths->where('rank', '10')->first();
        }

        // STRAIGHT FLUSH
        if ($this->hasFlush($cards) && $this->hasStraight($cards)) {
            return $this->strengths->where('rank', '9')->first();
        }

        // FOUR OF A KIND
        if ($this->hasXofAKind($counts, 4)) {
            return $this->strengths->where('rank', '8')->first();
        }

        // FULL HOUSE
        if (
            $this->hasXofAKind($counts, 3) &&
            $this->hasXofAKind($this->countsExcept($counts, $this->hasXofAKind($counts, 3)), 2)
        ) {
            return $this->strengths->where('rank', '7')->first();
        }

        // FLUSH
        if ($this->hasFlush($cards)) {
            return $this->strengths->where('rank', '6')->first();
        }

        // STRAIGHT
        if ($this->hasStraight($cards)) {
            return $this->strengths->where('rank', '5')->first();
        }

        // THREE OF A KIND
        if ($this->hasXofAKind($counts, 3)) {
            return $this->strengths->where('rank', '4')->first();
        }

        // TWO PAIR
        if (
            $this->hasXofAKind($counts, 2) &&
            $this->hasXofAKind($this->countsExcept($counts, $this->hasXofAKind($counts, 2)), 2)
        ) {
            return $this->strengths->where('rank', '3')->first();
        }

        // ONE PAIR
        if ($this->hasXofAKind($counts, 2)) {
            return $this->strengths->where('rank', '2')->first();
        }

        // HIGH CARD
        return $this->strengths->where('rank', '1')->first();
    }

    /**
     * @param $counts
     * @param $except
     * @return array
     */
    public function countsExcept($counts, $except): array
    {
        return array_filter($counts, function ($key) use ($except) {
            return $key !== $except;
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @param $counts
     * @param int $x
     * @return false|int|string
     */
    public function hasXofAKind($counts, int $x)
    {
        return array_search($x, $counts);
    }

    /**
     * @param $cards
     * @return array
     */
    public function getCounts($cards): array
    {
        $counts = [];

        foreach ($cards as $card) {
            if (array_key_exists($card->rank, $counts)) {
                $counts[$card->rank]++;
            } else {
                $counts[$card->rank] = 1;
            }
        }

        return $counts;
    }

    /**
     * @param $cards
     * @return bool
     */
    public function hasRoyal($cards): bool
    {
        $array = ['K', 'A', 'J', 'T', 'Q'];

        foreach ($cards as $card) {
            $rank = $card->rank;

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

    /**
     * @param $cards
     * @return bool
     */
    private function hasStraight($cards): bool
    {
        $indexedRank = self::MAPPING[$cards[0]->rank];

        for ($i = 1; $i < 4; $i++) {
            $cardIndexedRank = self::MAPPING[$cards[$i]->rank];

            if ($cardIndexedRank === $indexedRank + 1 || $cardIndexedRank === $indexedRank - 1) {
                $indexedRank = $cardIndexedRank;
            } else {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $cards
     * @return bool
     */
    private function hasFlush($cards): bool
    {
        $suit = $cards[0]->suit;

        for ($i = 1; $i <= 4; $i++) {
            if ($cards[$i]->suit !== $suit) {
                return false;
            }
        }

        return true;
    }
}
