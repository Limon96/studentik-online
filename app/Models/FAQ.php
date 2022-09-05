<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
{
    use HasFactory;

    protected $table = 'faq';
    protected $primaryKey = 'id';

    protected $fillable = [
        'sort_order',
        'status',
        'question',
        'answer',
        'faq_category_id',
    ];

}
