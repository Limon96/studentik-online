<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerTransaction extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'customer_transaction';

    protected $primaryKey = 'customer_transaction_id';

    protected $fillable = [
        'customer_id',
        'description',
        'amount',
        'balance',
        'date_added',
    ];

}
