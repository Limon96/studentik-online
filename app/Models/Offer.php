<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $table = 'offer';

    protected $primaryKey = 'offer_id';

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function isOwner()
    {
        return (int)$this->attributes['customer_id'] === auth()->user()->id ?? 0;
    }

    public function isAssigned()
    {
        return (int)$this->attributes['assigned'] === 1;
    }

}
