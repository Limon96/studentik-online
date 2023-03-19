<?php

namespace App\Components\Image;

use Illuminate\Support\Facades\File;

class Image
{

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

}
