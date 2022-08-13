<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Landing extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'menu_title',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'components',
        'slug',
        'type_page',
        'status',
    ];
 }
