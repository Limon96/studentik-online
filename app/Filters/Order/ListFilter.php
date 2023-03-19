<?php

namespace App\Filters\Order;

use App\Filters\QueryFilter;
use App\Models\InstructionDescription;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListFilter extends QueryFilter
{

    public function search($search = '')
    {
        return $this->builder
            ->where(function (Builder $query) use ($search) {
                return $query
                    ->where('title', 'LIKE', '%' . $search . '%')
                    ->orWhere('description', 'LIKE', '%' . $search . '%');
            });
    }

    public function filter_section_id($filter_section_id = '')
    {
        return $this->builder
            ->where(function (Builder $query) use ($filter_section_id) {
                if ($filter_section_id > 0) {
                    return $query->where('section_id', '=', $filter_section_id);
                }
            });
    }

    public function filter_subject_id($filter_subject_id = '')
    {
        return $this->builder
            ->where(function (Builder $query) use ($filter_subject_id) {
                if ($filter_subject_id > 0) {
                    return $query->where('subject_id', '=', $filter_subject_id);
                }
            });
    }

    public function filter_work_type_id($filter_work_type_id = '')
    {
        return $this->builder
            ->where(function (Builder $query) use ($filter_work_type_id) {
                if ($filter_work_type_id > 0) {
                    return $query->where('work_type_id', '=', $filter_work_type_id);
                }
            });
    }

    public function filter_no_offer($filter_no_offer = 0)
    {
        return $this->builder
            ->where(function (Builder $query) use ($filter_no_offer) {
                if ((int)$filter_no_offer === 1) {
                    return $query->whereNotIn('order_id', function ($query) {
                        return $query->from('offer')->select('order_id');
                    });
                }
            });
    }

    public function filter_my_specialization($filter_my_specialization = 0)
    {
        return $this->builder
            ->where(function (Builder $query) use ($filter_my_specialization) {
                if ((int)$filter_my_specialization === 1 && Auth::authenticate()) {
                    return $query->whereIn('subject_id', function ($query) {
                        return $query->from('customer_subject')
                            ->select('subject_id')
                            ->where('customer_id', Auth::user()->id);
                    });
                }
            });
    }

}
