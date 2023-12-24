<?php

namespace App\Filters\Admin;

use App\Filters\QueryFilter;
use App\Models\InstructionDescription;
use Illuminate\Database\Eloquent\Builder;

class UserFilter extends QueryFilter
{

    public function search($search = '')
    {
        return $this->builder
            ->where(function (Builder $query) use ($search) {
                return $query
                    ->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('email', 'LIKE', '%' . $search . '%')
                    ->orWhere('login', 'LIKE', '%' . $search . '%');
            });
    }

}
