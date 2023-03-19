<?php

return [
    'title' => 'Текстовый блок',
    'type' => 'text_block',
    'fields' => [
        [
            'label' => 'Заголовок',
            'name' => 'title',
            'type' => 'string',
        ],
        [
            'label' => 'Описание',
            'name' => 'text',
            'type' => 'summernote' // textarea
        ]
    ]
];
