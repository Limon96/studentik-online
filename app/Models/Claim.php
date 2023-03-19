<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    use HasFactory;

    protected $table = 'claim';
    protected $primaryKey = 'claim_id';

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', $this->primaryKey);
    }

    public function defendant()
    {
        return $this->belongsTo(Customer::class, 'defendant_id', $this->primaryKey);

    }
}
