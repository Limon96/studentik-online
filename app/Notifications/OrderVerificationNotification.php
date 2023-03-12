<?php

namespace App\Notifications;

use App\Mail\NotificationMail;
use App\Models\Customer;
use Illuminate\Support\Facades\Mail;

class OrderVerificationNotification extends Notification
{

    protected function sendNotification()
    {
        \App\Models\Notification::insert([
            'customer_id' => $this->customer->customer_id,
            'text' => __('notification.verification_offer', $this->replace),
            'comment' => $this->comment,
            'type' => self::ORDER_TYPE,
            'viewed' => 0,
            'date_added' => time()
        ]);
    }

    protected function sendMail()
    {
        $notificationMail = new NotificationMail(
            'Заказчик начал проверку готовой работы!',
            __('notification.verification_offer', $this->replace)
        );

        $notificationMail->to($this->customer->email);

        Mail::queue($notificationMail);
    }

}
