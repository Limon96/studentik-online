<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'order';
    protected $primaryKey = 'order_id';

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function offers()
    {
        return $this->hasMany(Offer::class, 'order_id', 'order_id');
    }

    public function getId()
    {
        return $this->attributes['order_id'];
    }

    public function getSlug()
    {
        return SeoUrl::where('query', 'order_id=' . $this->attributes['order_id'])->first()->keyword ?? null;
    }

}
