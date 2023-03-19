<?php

namespace App\Console\Commands;

use App\Mail\TimeChooseOffer;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class TimeChooseOfferCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:time_choose_offer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Time Choose Offer';

    private $days = 1;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $orders = $this->getOrders();

        $this->question($orders->count() . ' orders found');

        if ($orders) {
            foreach ($orders as $order) {
                if ($order->customer && $order->customer->setting_email_notify) {
                    $this->warn('Sending an email...');
                    $mail = new TimeChooseOffer($order);
                    $mail->to($order->customer->email);

                    Mail::send($mail);
                    $this->info('Sent an email');
                }
            }
        }

        $this->line('==========================');
        $this->info('The command was successful!');
        return 0;
    }

    private function getOrders()
    {
        return Order
            ::where('date_added', '>', now()->subDays($this->days + 1)->getTimestamp())
            ->where('date_added', '<', now()->subDays($this->days)->getTimestamp())
            ->where('order_status_id', 1)
            ->where(DB::raw(0), '<', function ($query) {
                return $query->from('offer', 'of')->where(DB::raw('`order`.order_id'), DB::raw('of.order_id'))->select(DB::raw('COUNT(1)'));
            })
            ->with(['customer', 'offers' => function ($query) {
                return $query->with('customer');
            }])
            ->get();
    }
}
