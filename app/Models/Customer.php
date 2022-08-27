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

    public function customerReviews()
    {
        return $this->hasMany(CustomerReview::class, 'customer_id', $this->primaryKey);
    }

    public function reviews()
    {
        return $this->hasManyThrough(Review::class, CustomerReview::class, 'customer_id', 'review_id', 'customer_id', 'review_id');
    }

    public function getImage()
    {
        if (empty($this->attributes['image'])) {
            return 'storage/image/profile.png';
        }

        return $this->attributes['image'];
    }

    public function getCountPositiveReviews()
    {
        return $this->reviews()->where('positive', '=', '1')->count();
    }

    public function getCountNegativeReviews()
    {
        return $this->reviews()->where('positive', '=', '0')->count();
    }

    public function getNewRating()
    {
        return $this->rating()->where('date_added', '>', (time() - 604800))->sum('rating');
    }
}
