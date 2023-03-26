<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlagiarismCheck extends Model
{
    use HasFactory;

    protected $table = 'plagiarism_check';
    protected $primaryKey = 'plagiarism_check_id';
}
