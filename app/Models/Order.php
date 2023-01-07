<?php

namespace App\Models;

use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'order';

    protected $primaryKey = 'order_id';

    protected $fillable = [
        'payment_blocking_id',
        'title',
        'work_type_id',
        'subject_id',
        'section_id',
        'date_end',
        'description',
        'premium',
        'hot',
        'plagiarism_check_id',
        'price',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function work_type()
    {
        return $this->belongsTo(WorkType::class, 'work_type_id', 'work_type_id');
    }

    public function order_status()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id', 'order_status_id');
    }

    public function offers()
    {
        return $this->hasMany(Offer::class, 'order_id', 'order_id');
    }

    public function plagiarism_check()
    {
        return $this->belongsTo(PlagiarismCheck::class, 'plagiarism_check_id', 'plagiarism_check_id');
    }

    public function getSeoUrl()
    {
        return SeoUrl::select('query', 'keyword')->where('query', 'order_id=' . $this->attributes[$this->primaryKey])->first()->keyword ?? '';
    }

    public function getDateEnd()
    {
        return $this->attributes['date_end'] !== '0000-00-00' ? format_date($this->attributes['date_end'], 'full_date') : 'не указан';
    }

    public function getPrice()
    {
        return $this->attributes['price'] > 0 ? format_currency($this->attributes['price']) : 'договорная';
    }

    public function getPlagiarismCheck()
    {
        return is_null($this->plagiarism_check) ? 'Не указан' : $this->plagiarism_check->name;
    }

    public function getPlagiarism()
    {
        return json_decode($this->attributes['plagiarism']);
    }

    public function scopeFilter(Builder $builder, QueryFilter $filter)
    {
        return $filter->apply($builder);
    }


    public function isOwner()
    {
        return (int)$this->attributes['customer_id'] === auth()->user()->id ?? 0;
    }

}
