<?php

namespace App\Notifications;

use App\Models\Customer;

abstract class Notification
{

    const ORDER_TYPE = 'order';

    public function __construct(
        protected readonly Customer $customer,
        protected readonly array $replace = [],
        protected readonly string $comment = ''
    )
    {
        //
    }

    public function send(bool $withMail = false)
    {
        $this->sendNotification();

        if ($withMail && $this->customer->setting_email_notify) {
            $this->sendMail();
        }
    }

    abstract protected function sendNotification();
    abstract protected function sendMail();

}
