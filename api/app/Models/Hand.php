<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hand extends Model
{
    use HasFactory;

    const CARDS_ID = [
        'first_card_id',
        'second_card_id',
        'third_card_id',
        'fourth_card_id',
        'fifth_card_id'
    ];

    protected $fillable = [
        'first_card_id',
        'second_card_id',
        'third_card_id',
        'fourth_card_id',
        'fifth_card_id',
        'player_id',
        'round_id'
    ];

    public function strength()
    {
        return $this->belongsTo(Strength::class);
    }

    public function firstCard()
    {
        return $this->belongsTo(Card::class);
    }

    public function secondCard()
    {
        return $this->belongsTo(Card::class);
    }

    public function thirdCard()
    {
        return $this->belongsTo(Card::class);
    }

    public function fourthCard()
    {
        return $this->belongsTo(Card::class);
    }

    public function fifthCard()
    {
        return $this->belongsTo(Card::class);
    }
}
