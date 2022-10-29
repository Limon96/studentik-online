<?php

namespace App\Jobs\Newsletter;

use App\Dtos\MailDto;
use App\Repositories\CustomerRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MailingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private MailDto $mailDto;
    private array $filterUsers;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(MailDto $mailDto, array $filterUsers = [])
    {
        $this->mailDto = $mailDto;
        $this->filterUsers = $filterUsers;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $users = app(CustomerRepository::class)->forMailing($this->filterUsers);

        foreach ($users as $user) {
            dispatch(
                new SendMailJob($user, $this->mailDto)
            );
            sleep(25);
        }

    }
}
