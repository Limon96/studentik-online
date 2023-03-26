<?php

return [
    'title' => 'Последние заказы',
    'type' => 'last_orders',
    'fields' => [
        [
            'label' => 'Тип работы',
            'name' => 'work_type',
            'type' => 'select',
            'values' => \App\Models\WorkType::selectRaw('`name`, `work_type_id` as `value`')->orderBy('name')->get()->map(function ($item) {
                return [
                    'name' => $item->name,
                    'value' => $item->value,
                ];
            })->toArray(),
        ],
    ],
];
