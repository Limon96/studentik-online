<?php

namespace App\Mail;

use App\Models\Order;
use App\Traits\MailToken;
use App\Traits\Unsubscribe;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TimeChooseOffer extends Mailable
{
    use Queueable, SerializesModels, Unsubscribe, MailToken;

    public $order;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
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
            $this->getSubject(),
            $html,
            $mail_token,
            $unsubscribe_token,
        );

        return $this
            ->subject($this->getSubject())
            ->html($html);
    }

    private function getSubject()
    {
        return 'Пришло время выбрать автора в заказе №' . $this->order->getId();
    }
    private function getHTML($mail_token, $unsubscribe_token)
    {
        return view('mail.time_choose_offer', [
            'order' => $this->order,
            'token' => $mail_token,
            'unsubscribe_token' => $unsubscribe_token,
        ])->render();
    }
}
