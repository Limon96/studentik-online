<?php

return [
    'title' => 'Блок FAQ',
    'type' => 'faq_block',
    'fields' => [
        [
            'label' => 'Заголовок',
            'name' => 'title',
            'type' => 'string'
        ],
        [
            'label' => 'Группа блоков',
            'type' => 'fields',
            'fields' => []
        ]
    ]
];
