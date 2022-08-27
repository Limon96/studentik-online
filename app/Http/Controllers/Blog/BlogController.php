<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Repositories\BlogPostRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class BlogController extends Controller {

    /**
     * @param string $slug
     * @return Application|Factory|View
     */
    public function index(string $slug)
    {
        $item = app(BlogPostRepository::class)->findSlug($slug);

        return view('blog.index', compact(
            'item'
        ));
    }

}
