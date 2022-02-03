<?php

namespace Database\Seeders;

use App\Models\Strength;
use Illuminate\Database\Seeder;

class StrengthsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Strength::STRENGTHS as $key => $strength) {
            Strength::create([
                'name' => $strength,
                'rank' => 10 - $key
            ]);
        }
    }
}
