<?php

namespace Tests\Feature\API;

use App\Models\ClientToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SMTPControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_send()
    {
        $token = ClientToken::get()->first()->token;

        $response = $this->post('/api/smtp', [
            'to' => 'nicker08@inbox.ru',
            'subject' => 'Subject test',
            'message' => '<h1>Hello world!</h1>',
            'token' => $token
        ]);

        $response->assertStatus(200);
    }
}
