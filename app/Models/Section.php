<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'section_id', 'id')->orderBy('name');
    }
}
