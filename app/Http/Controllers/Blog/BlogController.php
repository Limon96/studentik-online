<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;

class BlogController extends Controller {

    public function index()
    {
        return view('blog.index');
    }

}
