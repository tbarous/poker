<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    use HasFactory;

    public static function getLastId(): int
    {
        $latestRound = self::latest();

        if (empty($latestRoundId)) {
            return 1;
        }

        return $latestRound->id;
    }

    public function hands()
    {
        return $this->hasMany(Hand::class);
    }
}
