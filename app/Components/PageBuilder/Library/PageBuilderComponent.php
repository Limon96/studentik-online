<?php

namespace App\Components\PageBuilder\Library;

class PageBuilderComponent {

    public static function get($component)
    {
        if (is_file(__DIR__ . '/Components/' . $component . '.php')) {
            return include(__DIR__ . '/Components/' . $component . '.php');
        }
        return '';
    }

    public static function render($nameComponent)
    {
        $component = self::get($nameComponent);

        if (!is_null($component)) {
            return view('pagebuilder.component', $component);
        }

        return '';
    }

}
