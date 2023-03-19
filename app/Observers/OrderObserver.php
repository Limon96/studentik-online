<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Section;
use App\Models\SeoUrl;
use App\Models\Subject;
use Illuminate\Support\Str;

class OrderObserver
{

    public function created(Order $order)
    {
        $this->setSeoUrl($order);
    }

    public function creating(Order $order)
    {
        $this->setWorkType($order);
        $this->setSubject($order);
        $this->setSection($order);
        $this->setCustomer($order);
        $this->setOrderStatus($order);
        $this->setPlagiarism($order);
        $this->setPremium($order);
        $this->setHot($order);
        $this->setIp($order);
        $this->setDateAdded($order);
        $this->setDateModified($order);
        $this->setDateEnd($order);
        $this->setDescription($order);
        $this->setPrice($order);
    }

    private function setWorkType(Order $order)
    {
        $order->work_type_id = (int)request()->work_type ?? 0;
    }

    private function setSubject(Order $order)
    {
        $order->subject_id = (int)request()->subject ?? 0;
    }

    private function setSection(Order $order)
    {
        $order->section_id = Subject::find($order->subject_id)->section_id ?? 0;
    }

    private function setCustomer(Order $order)
    {
        $order->customer_id = auth()->user()->id;
        $order->customer_group_id = auth()->user()->customer->customer_group_id;
    }

    private function setOrderStatus(Order $order)
    {
        $order->order_status_id = 1;
    }

    private function setPremium(Order $order)
    {
        if (is_null($order->premium)) {
            $order->premium = 0;
        }
    }

    private function setHot(Order $order)
    {
        if (is_null($order->hot)) {
            $order->hot = 0;
        }
    }

    private function setPlagiarism(Order $order)
    {
        if (is_null($order->plagiarism)) {
            $order->plagiarism = [];
        }

        $order->plagiarism = json_encode($order->plagiarism);
    }

    private function setIp(Order $order)
    {
        $order->ip = request()->ip();
    }

    private function setDateAdded(Order $order)
    {
        $order->date_added = time();
    }

    private function setDateModified(Order $order)
    {
        $order->date_modified = '0000-00-00 00:00:00';
    }

    private function setDateEnd(Order $order)
    {
        if (!$order->date_end) {
            $order->date_end = '0000-00-00';
        }

    }

    private function setDescription(Order $order)
    {
        if (is_null($order->description)) {
            $order->description = '';
        }
    }

    private function setPrice(Order $order)
    {
        if (is_null($order->price)) {
            $order->price = 0;
        }
    }

    private function setSeoUrl(Order $order)
    {
        SeoUrl::create([
            'language_id' => 1,
            'store_id' => 0,
            'query' => 'order_id=' . $order->order_id,
            'keyword' => $order->order_id . '-' . Str::slug($order->title),
        ]);
    }

}
