<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic unit test example.
     *
     * @test
     * @return void
     */
    public function players_can_register()
    {
        $response = $this->post('/api/register', [
            'name' => 'Anastasios',
            'email' => 'email@email.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response->assertStatus(200);
    }
}
