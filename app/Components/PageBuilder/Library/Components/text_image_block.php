<?php

return [
    'title' => 'Текстовый блок',
    'type' => 'text_image_block',
    'fields' => [
        [
            'label' => 'Заголовок',
            'name' => 'title',
            'type' => 'string',
        ],
        [
            'label' => 'Ссылка на изображение',
            'name' => 'image',
            'type' => 'string',
        ],
        [
            'label' => 'Описание',
            'name' => 'text',
            'type' => 'textarea' // textarea summernote
        ]
    ]
];
