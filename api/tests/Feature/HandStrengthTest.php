<?php

namespace Tests\Feature;

use App\Models\Card;
use App\StrengthCalculator;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class HandStrengthTest extends TestCase
{
    public $strengthCalculator;
    public $deck;

    protected function setUp(): void
    {
        parent::setUp();

        $this->strengthCalculator = new StrengthCalculator();
        $this->deck = Card::all();
    }

    /**
     * Check if a hand is royal flush.
     *
     * @test
     * @return void
     */
    public function is_royal_flush()
    {
        $cards = [
            $this->deck->where('suit', 'H')->where('rank', 'K')->first(),
            $this->deck->where('suit', 'H')->where('rank', 'Q')->first(),
            $this->deck->where('suit', 'H')->where('rank', 'J')->first(),
            $this->deck->where('suit', 'H')->where('rank', 'T')->first(),
            $this->deck->where('suit', 'H')->where('rank', 'A')->first(),
        ];

        $strength = $this->strengthCalculator->getStrength($cards)->name;

        $this->assertEquals($strength, 'Royal Flush');
    }

    /**
     * Check if a hand is royal flush.
     *
     * @test
     * @return void
     */
    public function is_flush()
    {
        $cards = [
            $this->deck->where('suit', 'S')->where('rank', 'K')->first(),
            $this->deck->where('suit', 'S')->where('rank', 'Q')->first(),
            $this->deck->where('suit', 'S')->where('rank', '2')->first(),
            $this->deck->where('suit', 'S')->where('rank', 'T')->first(),
            $this->deck->where('suit', 'S')->where('rank', '5')->first(),
        ];

        $strength = $this->strengthCalculator->getStrength($cards)->name;

        $this->assertEquals($strength, 'Flush');
    }

    /**
     * Check if a hand is a straight.
     *
     * @test
     * @return void
     */
    public function is_straight()
    {
        $cards = [
            $this->deck->where('suit', 'S')->where('rank', 'K')->first(),
            $this->deck->where('suit', 'H')->where('rank', 'Q')->first(),
            $this->deck->where('suit', 'H')->where('rank', 'J')->first(),
            $this->deck->where('suit', 'H')->where('rank', 'T')->first(),
            $this->deck->where('suit', 'H')->where('rank', 'A')->first(),
        ];

        $strength = $this->strengthCalculator->getStrength($cards)->name;

        $this->assertEquals($strength, 'Straight');
    }
}
