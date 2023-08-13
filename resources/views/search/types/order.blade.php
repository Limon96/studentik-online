<div class="item_s task_p">

    <span class="status"><i class="fa fa-flag"></i>{{ $item->model->order_status->title }}</span>
    <a href="{{ route('order.show', $item->model->getSeoUrl()) }}"><h4>{{ $item->title }}</h4></a>
    <p class="minidescription">{{ $item->description }}</p>
    <ul class="pagination_s clearfix">
        <li>{{ $item->model->work_type->title }}</li>
        <li>{{ $item->model->section->title }}</li>
        <li>{{ $item->model->subject->title }}</li>


    </ul>
</div>
