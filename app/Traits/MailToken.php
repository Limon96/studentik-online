<?php

namespace App\Traits;

use App\Models\SentEmail;

trait MailToken {

    public function createMail($to, $subject, $body, $token = '', $unsubscribe_token = '', $status = 0)
    {
        return SentEmail::create([
            'to' => $to,
            'subject' => $subject,
            'body' => $body,
            'token' => $token,
            'unsubscribe_token' => $unsubscribe_token,
            'status' => $status,
        ]);
    }

    public function generateMailToken($email, $subject)
    {
        return hash('sha256', $email . $subject . microtime(true));
    }

}
