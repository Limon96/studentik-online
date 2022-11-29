<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Illuminate\Foundation\Testing\Concerns\InteractsWithSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SignInControllerTest extends TestCase
{


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_page()
    {
        $response = $this->get('/sign_in');

        $response->assertStatus(200);
    }

    public function test_auth()
    {
        $response = $this->postJson('/sign_in', [
            'login' => 'nicker08@inbox.ru',
            'password' => '12345678'
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'redirect'
            ]);
    }
}
