<?php

namespace App\Filters\Admin\Feedback;

use App\Filters\QueryFilter;
use App\Models\InstructionDescription;
use Illuminate\Database\Eloquent\Builder;

class SentEmailFilter extends QueryFilter
{

    public function search($search = '')
    {
        return $this->builder
            ->where(function (Builder $query) use ($search) {
                return $query
                    ->where('to', 'LIKE', '%' . $search . '%')
                    ->orWhere('subject', 'LIKE', '%' . $search . '%')
                    ->orWhere('body', 'LIKE', '%' . $search . '%');
            });
    }

}
