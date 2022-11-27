<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkType extends Model
{
    use HasFactory;

    protected $table = 'work_type';
    protected $primaryKey = 'work_type_id';
    protected $fillable = [
        'name',
        'sort_order',
        'language_id'
    ];

}
