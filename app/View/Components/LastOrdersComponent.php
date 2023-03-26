<?php

namespace App\View\Components;

use App\Models\Order;
use Illuminate\View\Component;

class LastOrdersComponent extends Component
{
    private int $work_type;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(int|string $workType = 0)
    {
        $this->work_type = (int)$workType;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if ($this->work_type) {
            $lastOrders = Order
                ::where('work_type_id', $this->work_type)
                ->where('order_status_id', 6)
                ->limit(10)
                ->orderByDesc('order_id')
                ->get();


        } else {
            $lastOrders = Order
                ::where('order_status_id', 6)
                ->limit(10)
                ->orderByDesc('order_id')
                ->get();
        }

        return view('components.last-orders-component', compact('lastOrders'));
    }
}
