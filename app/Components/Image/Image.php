<?php

namespace App\Components\Image;

use Illuminate\Support\Facades\File;

class Image
{

    const NO_IMAGE = 'storage/image/no_image.jpg';

    public static function replaceBase64FromHTML($html)
    {
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'utf-8');

        $dom = new \DOMDocument();
        $dom->loadHtml($html);
        $image_file = $dom->getElementsByTagName('img');

        if (!File::exists(public_path('uploads'))) {
            File::makeDirectory(public_path('uploads'));
        }

        foreach ($image_file as $key => $image) {
            $data = $image->getAttribute('src');

            $data = explode(',', $data);

            if (!isset($data[1])) continue;

            $img_data = base64_decode($data[1]);

            $image_name = "/uploads/" . (microtime(true) * 1000)  . $key . '.png';
            $path = public_path() . $image_name;
            file_put_contents($path, $img_data);

            $image->removeAttribute('src');
            $image->setAttribute('src', $image_name);
        }

        $html = $dom->saveHTML();

        return $html;
    }

    public static function resize($imagePublicPath, int $width = 640, int $height = null)
    {
        $imageFilePath = self::getStoragePath($imagePublicPath);

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

        return $thumbnailPublicPath;
    }

    protected static function getStoragePath($imagePublicPath) {

        $imageFilePath = storage_path(str_replace(['storage/'], ['app/public/'], $imagePublicPath));

        if (file_exists($imageFilePath)) {
            return $imageFilePath;
        }

        $imagePublicPath = self::NO_IMAGE;

        return storage_path(str_replace(['storage/'], ['app/public/'], $imagePublicPath));
    }

}
