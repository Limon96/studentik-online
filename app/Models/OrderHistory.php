<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    use HasFactory;

    protected $table = 'order_history';

    protected $primaryKey = 'order_history_id';

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function render()
    {
        $customer = view('components.customer.min_info', [
            'customer' => $this->customer,
        ])->render();

        return sprintf($this->text, $customer);
    }

}
