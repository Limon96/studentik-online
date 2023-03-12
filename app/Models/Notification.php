<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notification';
    protected $primaryKey = 'notification_id';
    protected $fillable = [
        'customer_id',
        'text',
        'comment',
        'type',
        'viewed',
        'date_added',
    ];

}
