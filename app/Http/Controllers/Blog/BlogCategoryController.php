<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Repositories\BlogCategoryRepository;
use App\Repositories\BlogPostRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class BlogCategoryController extends Controller {

    /**
     * @return Application|Factory|View
     */
    public function index(string $slug = '')
    {
        $item = app(BlogCategoryRepository::class)->findSlug($slug);

        $blogCategories = app(BlogCategoryRepository::class)->all();

        if ($item) {
            $blogPosts = $item
                ->posts()
                ->orderByDesc('created_at')
                ->paginate(10);
        } else {
            $blogPosts = app(BlogPostRepository::class)->paginate(10);
        }

        get_widget('top_customer');

        return view('blog_category.index', compact(
            'item',
            'blogCategories',
            'blogPosts'
        ));
    }

}
