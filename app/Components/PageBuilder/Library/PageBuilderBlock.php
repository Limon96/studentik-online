<?php

namespace App\Components\PageBuilder\Library;

use App\Components\PageBuilder\Library\Blocks\Block;

class PageBuilderBlock {

    public static function convert(array $components): array
    {
        $blocks = [];
        if ($components) {
            foreach ($components as $component) {
                $object = new Block(
                    $component['title'],
                    $component['type'],
                    $component['fields']
                );

                $blocks[] = $object->toArray();
            }
        }

        return $blocks;
    }

    /**
     * @param string $type
     * @return string
     */
    private static function typeToClassName(string $type): string
    {
        $arr = explode('_', $type);

        foreach ($arr as &$item)
            $item = ucfirst($item);

        return join($arr);
    }


}
