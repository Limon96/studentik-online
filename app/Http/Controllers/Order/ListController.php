<?php

namespace App\Http\Controllers\Order;

use App\Filters\Order\ListFilter;
use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Repositories\OrderRepository;
use App\Repositories\SectionRepository;
use App\Repositories\WorkTypeRepository;

class ListController extends Controller
{
    const COUNT_ORDERS_PER_PAGE = 10;
    const ORDER_STATUS_ID = 1;

    public function __invoke(ListFilter $request)
    {
        $sections = app(SectionRepository::class)->getForSelectWithoutSubjects();
        $subjects = [];
        if ($request->request->filter_section_id ?? 0) {
            $subjects = Subject::orderBy('name')->select(['id', 'name'])->where('section_id', $request->request->filter_section_id)->get();
        }
        $work_types = app(WorkTypeRepository::class)->getForSelect();

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
