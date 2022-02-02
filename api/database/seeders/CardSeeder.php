<?php

namespace Database\Seeders;

use App\Models\Card;
use Illuminate\Database\Seeder;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Card::Suits as $suit) {
            foreach (Card::Ranks as $rank) {
                Card::create([
                    'suit' => $suit,
                    'rank' => $rank
                ]);
            }
        }
    }
}
