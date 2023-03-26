<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'order';
    protected $primaryKey = 'order_id';

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'subject_id');
    }

    public function work_type()
    {
        return $this->belongsTo(WorkType::class, 'work_type_id', 'work_type_id');
    }

    public function plagiarism_check()
    {
        return $this->belongsTo(PlagiarismCheck::class, 'plagiarism_check_id', 'plagiarism_check_id');
    }

    public function offers()
    {
        return $this->hasMany(Offer::class, 'order_id', 'order_id');
    }

    public function offerAssigned()
    {
        return $this->belongsTo(Offer::class, 'order_id', 'order_id')->where('assigned', 1);
    }

    public function getId()
    {
        return $this->attributes['order_id'];
    }

    public function getSlug()
    {
        return SeoUrl::where('query', 'order_id=' . $this->attributes['order_id'])->first()->keyword ?? null;
    }

}
