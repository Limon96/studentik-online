<?php

namespace App\Traits;

use App\Models\UnsubscribeToken;
use App\Models\User;

trait Unsubscribe {

    public function generateUnsubscribeToken($email)
    {
        $token = hash('sha256', $email . microtime(true));

        $this->saveToken($email, $token);

        return $token;
    }

    public function saveToken($email, $token)
    {
        UnsubscribeToken::create([
            'email' => $email,
            'token' => $token
        ]);
    }

    public function validateToken($token)
    {
        if (is_null($token)) {
            return false;
        }

        if (!UnsubscribeToken::exists(['token' => $token])) {
            return false;
        }

        return true;
    }

    public function activateToken($token)
    {
        $email = UnsubscribeToken::where('token', $token)->get()->first()->email;

        User::where('email', $email)->update(['is_subscribed' => 0]);
    }

}
