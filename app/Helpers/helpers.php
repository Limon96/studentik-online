<?php

if (!function_exists('thumbnail')) {

    function thumbnail($imagePublicPath, int $width = 640, int $height = null)
    {
        $thumbnailPublicPath = \App\Components\Image\Image::resize($imagePublicPath, $width, $height);

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
        } else {
            return date($format, strtotime($str));
        }
    }

}
