<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class BlogCategoryController extends Controller {

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('blog.index');
    }

    /**
     * @param string $slug
     * @return Application|Factory|View
     */
    public function show(string $slug)
    {
        return view('blog.index');
    }

}
