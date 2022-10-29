<?php

namespace App\Mail;

use App\Traits\MailToken;
use App\Traits\Unsubscribe;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Newsletter extends Mailable
{
    use Queueable, SerializesModels, Unsubscribe, MailToken;

    public $subject;
    public $body;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $subject, string $body)
    {
        $this->subject = $subject;
        $this->body = $body;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->to[0]['address'];

        $unsubscribe_token = $this->generateUnsubscribeToken($email);

        $mail_token = $this->generateMailToken($email, $this->subject);

        $html = $this->getHTML($mail_token, $unsubscribe_token);

        $this->createMail(
            $email,
            $this->subject,
            $html,
            $mail_token,
            $unsubscribe_token,
        );

        return $this
            ->subject($this->subject)
            ->html($html);
    }

    public function getHTML($mail_token, $unsubscribe_token)
    {
        return view('mail.newsletter', [
            'body' => $this->body,
            'token' => $mail_token,
            'unsubscribe_token' => $unsubscribe_token,
        ])->render();
    }
}
