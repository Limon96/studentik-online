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

    public function notifications()
    {
        return $this->hasMany(Notification::class, $this->primaryKey, $this->primaryKey);
    }

    public function totalUnreadNotifications()
    {
        if (!isset($this->attributes['totalUnreadNotifications'])) {
            $this->attributes['totalUnreadNotifications'] = $this->notifications()->where('viewed', 0)->count();
        }

        return $this->attributes['totalUnreadNotifications'];
    }

    public function totalUnreadMessages()
    {
        if (!isset($this->attributes['totalUnreadMessages'])) {
            $this->attributes['totalUnreadMessages'] = $this->hasMany(Message::class, 'recipient_id', $this->primaryKey)->where('viewed', 0)->count();
        }

        return $this->attributes['totalUnreadMessages'];
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

    public function isOnline()
    {
        return time() - $this->last_seen > 900;
    }

    public function isCustomer()
    {
        return $this->customer_group_id === 1;
    }

    public function isAuthor()
    {
        return $this->customer_group_id === 2;
    }

    public function deductBalanceTransfer(int $amount)
    {
        if ($amount > 0) {
            $amount = $amount * (-1);
        }

        $this->balanceTransfer($amount);
    }

    public function balanceTransfer(int $amount)
    {
        $this->balance += $amount;
        $this->save();
    }

    public function isPro()
    {
        return false; // TODO NICK: add real data
    }

}
