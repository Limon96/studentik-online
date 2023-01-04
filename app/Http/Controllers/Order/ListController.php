<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Section;
use App\Models\Subject;
use App\Models\WorkType;

class ListController extends Controller
{

    public function __invoke()
    {
        $sections = Section::orderBy('name')->select(['id', 'name'])->get();
        $subjects = Subject::orderBy('name')->select(['id', 'name'])->get();
        $work_types = WorkType::orderBy('name')->select(['work_type_id', 'name'])->get();
        $orders = Order::orderByDesc('date_added')->paginate(10);

        return view('order.list', compact(
            'sections',
            'subjects',
            'work_types',
            'orders'
        ));
    }

}
