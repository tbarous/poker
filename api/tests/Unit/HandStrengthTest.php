<?php

namespace Tests\Unit;

use App\Models\Card;
use App\StrengthCalculator;
use PHPUnit\Framework\TestCase;

class HandStrengthTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @test
     * @return void
     */
    public function is_royal_flush()
    {
        $strengthCalculator = new StrengthCalculator();

        $deck = Card::all();

        dd($deck);

        $cards = [
            $deck->where('suit', 'H')->where('rank', 'K'),
            $deck->where('suit', 'H')->where('rank', 'Q'),
            $deck->where('suit', 'H')->where('rank', 'J'),
            $deck->where('suit', 'H')->where('rank', 'T'),
            $deck->where('suit', 'H')->where('rank', 'A'),
        ];

        $strength = $strengthCalculator->strengthId($cards);

        $this->assertEquals($strength->id, 5);
    }
}
