<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    use HasFactory;

    public function getLastRoundId()
    {
        $latestRound = self::latest();

        if (empty($latestRoundId)) {
            $latestRoundId = 1;
        } else {
            $latestRoundId = $latestRound->id;
        }
    }
}
