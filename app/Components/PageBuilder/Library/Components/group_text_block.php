<?php

return [
    'title' => 'Группа текстовых блоков',
    'type' => 'group_text_block',
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
