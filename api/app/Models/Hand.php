<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hand extends Model
{
    use HasFactory;

    public function __construct($array)
    {

    }

    const CARDS_ID = ['first_card_id', 'second_card_id', 'third_card_id', 'fourth_card_id', 'fifth_card_id'];

    protected $fillable = [
        'first_card_id',
        'second_card_id',
        'third_card_id',
        'fourth_card_id',
        'fifth_card_id',
        'player_id',
        'round_id'
    ];

    public function calculateStrength()
    {
        $strength = 10;

//        $suits = [];
//        $ranks = [];
//
//        foreach ($cards as $card) {
//            $cardSplit = explode($card, "");
//
//            $ranks[] = $cardSplit[0];
//            $suits[] = $cardSplit[1];
//        }
//
//        $flush = $this->flush($suits);

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
