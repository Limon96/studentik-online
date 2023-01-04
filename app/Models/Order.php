<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;

    protected $table = 'order';

    protected $primaryKey = 'order_id';

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

    public function getSeoUrl()
    {
        return DB::table('seo_url', 'su')->select('query', 'keyword')->where('query', 'order_id=' . $this->attributes[$this->primaryKey])->first()->keyword ?? '';
    }

    public function getDateEnd()
    {
        return $this->attributes['date_end'] !== '0000-00-00' ? format_date($this->attributes['date_end'], 'full_date') : 'не указан';
    }

    public function getPrice()
    {
        return $this->attributes['price'] > 0 ? $this->attributes['price']. 'р.' : 'договорная';
    }

}
