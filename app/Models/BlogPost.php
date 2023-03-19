<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'intro',
        'text',
        'image',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'tags',
        'views',
        'status',
        'publish_at',
        'blog_category_id',
    ];

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id', 'id');
    }

    public function addView()
    {
        $this->views++;
        $this->save();

        return $this;
    }
}
