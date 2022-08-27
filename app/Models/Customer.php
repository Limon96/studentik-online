<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customer';
    protected $primaryKey = 'customer_id';

    public function rating()
    {
        return $this->hasMany(CustomerRating::class, 'customer_id', $this->primaryKey);
    }

}
