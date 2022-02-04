<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    use HasFactory;

    protected $appends = ['winner'];

    public function hands()
    {
        return $this->hasMany(Hand::class);
    }

    public function getWinnerAttribute()
    {
        $hands = $this->hands()->get();

        return $hands->min('strength')->name;
    }
}
