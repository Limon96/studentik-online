<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnsubscribeToken extends Model
{
    use HasFactory;

    protected $table = 'unsubscribe_token';

    protected $fillable = [
        'email',
        'token',
    ];
}
