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

    function format_date($str, $format = "d.m.Y")
    {

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

if (!function_exists('format_currency')) {

    function format_currency(float $number = 0, string $currency = 'RUB', $thousands_separator = ' ', string $decimal_separator = '.')
    {
        return \App\Currencies\Currency::format($number, $currency, $thousands_separator, $decimal_separator);
    }

}

if (!function_exists('format_size')) {
    function format_size($bytes, $decimals = 0)
    {
        $base = log($bytes, 1024);
        $suffixes = array('', 'Kбайт', 'Mбайт', 'Гбайт', 'Tбайт');

        return round(pow(1024, $base - floor($base)), $decimals) . ' ' . $suffixes[floor($base)];
    }
}

if (!function_exists('setting')) {
    function setting(string $key)
    {
        return \App\Models\Setting::where('key', htmlspecialchars($key))->select('value')->first()->value ?? '';
    }
}
