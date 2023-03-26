<?php

if (!function_exists('thumbnail')) {

    function thumbnail($imagePublicPath, int $width = 640, int $height = null)
    {
        $imageFilePath = storage_path(str_replace(['storage/'], ['app/public/'], $imagePublicPath));

        $thumbnailPublicPath = str_replace('storage/', 'storage/thumbnail/w' . $width . '/', $imagePublicPath);
        $thumbnailFilePath = storage_path(str_replace(['storage/'], ['app/public/thumbnail/w' . $width . '/'], $imagePublicPath));

        if (file_exists($thumbnailFilePath)) {
            return asset($thumbnailPublicPath);
        }

        if (!file_exists(dirname($thumbnailFilePath))) {
            mkdir(dirname($thumbnailFilePath), 0777, true);
        }

        $img = \Intervention\Image\Facades\Image::make($imageFilePath);

        $img->resize($width, $height, function ($const) {
            $const->aspectRatio();
        })->save($thumbnailFilePath);

        return asset($thumbnailPublicPath);
    }

}

if (!function_exists('get_widget')) {

    function get_widget(string $widgetName)
    {
        $widgetName = join('', array_map(function ($item) {
            return ucfirst(strtolower($item));
        }, explode('_', $widgetName)));

        $widgetClassName = 'App\\Components\\Widgets\\' . $widgetName . '\\' . $widgetName;

        if (class_exists($widgetClassName)) {
            return $widgetClassName::run();
        }

        return '';
    }

}

if (!function_exists('format_date')) {

    function format_date($str, $format = "d.m.Y") {

        if (is_numeric($str)) {
            $str = date("Y-m-d H:i:s", $str);
        }

        if ($format == "full_datetime") {
            $month = ['', 'Янв', 'Фев', 'Мар', 'Апр', 'Мая', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'];

            $ts = strtotime($str);

            $m = $month[date('n', $ts)];

            return str_replace('%', $m, date('j % Y в H:i', $ts));
        } elseif ($format == "full_date") {
            $month = ['', 'Янв', 'Фев', 'Мар', 'Апр', 'Мая', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'];

            $ts = strtotime($str);

            $m = $month[date('n', $ts)];

            return str_replace('%', $m, date('j % Y', $ts));
        } elseif ($format == "dMt") {
            $month = ['', 'Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня', 'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря'];

            $ts = strtotime($str);

            $m = $month[date('n', $ts)];

            return str_replace('%', $m, date('j % H:i', $ts));
        } elseif ($format == "dM") {
            $month = ['', 'Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня', 'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря'];

            $ts = strtotime($str);

            $m = $month[date('n', $ts)];

            return str_replace('%', $m, date('j %', $ts));
        } else {
            return date($format, strtotime($str));
        }
    }

}
