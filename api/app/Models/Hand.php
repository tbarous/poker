<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hand extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_1',
        'card_2',
        'card_3',
        'card_4',
        'card_5'
    ];

    public function getStrength($cards)
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

    private function straight($ranks)
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
}
