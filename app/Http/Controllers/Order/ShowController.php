<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;

class ShowController extends Controller
{

    public function __invoke(string $slug)
    {
        $item = app(OrderRepository::class)->findSlug($slug);

        if (is_null($item)) {
            return abort(404);
        }

        $item->incrementViewed();

        return view('order.show', compact(
            'item'
        ));
    }

}
