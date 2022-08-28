<?php

namespace App\Components\Widgets\BlogLatest;

use App\Components\Widgets\Widget;
use App\Repositories\BlogPostRepository;

class BlogLatest implements Widget {

    public static function run()
    {
        $items = app(BlogPostRepository::class)->latest(6);

        return view('widgets.blog_latest', compact(
            'items'
        ));
    }

}
