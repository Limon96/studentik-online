<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    protected $table = 'withdrawal';
    protected $primaryKey = 'withdrawal_id';

    protected $fillable = [
        'status',
        'amount',
        'balance',
        'comment',
        'method',
        'card_number',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }
}
