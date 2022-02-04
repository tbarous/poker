<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $fillable = ['suit', 'rank'];

    protected $attributes = [
        'suit',
        'rank'
    ];

    const Suits = ['H', 'C', 'S', 'D'];
    const Ranks = ['2', '3', '4', '5', '6', '7', '8', '9', 'T', 'J', 'Q', 'K', 'A'];

    /**
     * @return string
     */
    public function getSuit(): string
    {
        return $this->suit;
    }

    /**
     * @return string
     */
    public function getRank(): string
    {
        return $this->rank;
    }

    /**
     * @param $card
     * @return mixed|string
     */
    public static function getSuitFromString($card)
    {
        $split = str_split($card);

        return $split[1];
    }

    /**
     * @param $card
     * @return mixed|string
     */
    public static function getRankFromString($card)
    {
        $split = str_split($card);

        return $split[0];
    }
}
