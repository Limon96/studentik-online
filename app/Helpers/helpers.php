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
