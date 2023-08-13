<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Http\Requests\Search\IndexRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Services\MultiTableSearchService;

class IndexController extends Controller
{

    private array $models = [
        'order' => Order::class,
        'customer' => Customer::class,
    ];

    public function __invoke(IndexRequest $request)
    {
        $data = $request->validated();

        $searchService = new MultiTableSearchService();

        if (isset($data['search_order']) && $data['search_order'] == 1) {
            $searchService->setModel(Order::class, "order_id AS id, 'order' AS type, title, description, date_added");
        }

        if (isset($data['search_customer']) && $data['search_customer'] == 1) {
            $searchService->setModel(Customer::class, "customer_id AS id, 'customer' AS type, login AS title, '' AS description, date_added");
        }

        $results = $searchService->search($data['search']);

        if ($results) {
            $results = $this->complete($results);
        }

        return view('search.index', compact('results'));
    }

    private function complete($paginate)
    {
        $ids = [];

        foreach ($paginate as $item) {
            $ids[$item->type][] = $item->id;
        }

        $models = [];
        foreach ($ids as $type => $arrayId) {
            $model = new $this->models[$type];

            $results = $model->whereIn($model->getForeignKey(), $arrayId)->get();

            $models[$type] = [];
            foreach ($results as $result) {
                $models[$type][$result->{$model->getForeignKey()}] = $result;
            }
        }

        foreach ($paginate as $item) {
            $model = $models[$item->type][$item->id];

            foreach ($model->toArray() as $key => $value) {
                $item->$key = $value;
                $item->model = $model;
            }
        }

        return $paginate;
    }

}
