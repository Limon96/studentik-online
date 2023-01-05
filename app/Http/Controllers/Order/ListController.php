<?php

namespace App\Http\Controllers\Order;

use App\Filters\Order\ListFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\SubjectRequest;
use App\Models\Order;
use App\Models\Section;
use App\Models\Subject;
use App\Models\WorkType;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\DB;

class ListController extends Controller
{
    const COUNT_ORDERS_PER_PAGE = 10;
    const ORDER_STATUS_ID = 1;

    public function __invoke(ListFilter $request)
    {
        $sections = Section::orderBy('name')->select(['id', 'name'])->get();
        $subjects = [];
        if ($request->request->filter_section_id ?? 0) {
            $subjects = Subject::orderBy('name')->select(['id', 'name'])->where('section_id', $request->request->filter_section_id)->get();
        }
        $work_types = WorkType::orderBy('name')->select(['work_type_id', 'name'])->orderBy('sort_order')->get();

        $orders = app(OrderRepository::class)
            ->filterListOrder(
                $request,
                static::ORDER_STATUS_ID,
                static::COUNT_ORDERS_PER_PAGE
            );

        return view('order.list', compact(
            'sections',
            'subjects',
            'work_types',
            'orders'
        ));
    }

}
