<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FAQCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'title'
    ];

    public function questions()
    {
        return $this->hasMany(FAQ::class, 'faq_category_id', 'id');
    }
}
