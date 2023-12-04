<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payment';

    public $timestamps = false;

    protected $primaryKey ='payment_id';

    protected $fillable = [
        'customer_id',
        'platform_payment_id',
        'amount',
        'currency',
        'payment_status_id',
        'ip',
        'date_added',
        'date_updated',
    ];

    protected $casts = [
        'date_added' => 'datetime',
        'date_updated' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

}
