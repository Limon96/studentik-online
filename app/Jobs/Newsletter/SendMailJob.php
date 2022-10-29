<?php

namespace App\Jobs\Newsletter;

use App\Dtos\MailDto;
use App\Mail\Newsletter;
use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    private $mailDto;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Customer $user, MailDto $mailDto)
    {
        $this->user = $user;
        $this->mailDto = $mailDto;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mail = new Newsletter(
            $this->mailDto->subject,
            $this->mailDto->body
        );

        $mail->to($this->user->email);

        Mail::send($mail);
    }
}
