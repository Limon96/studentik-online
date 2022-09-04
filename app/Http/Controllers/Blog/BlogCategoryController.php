<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Repositories\BlogCategoryRepository;
use App\Repositories\BlogPostRepository;
use App\Traits\HasBlogCategory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class BlogCategoryController extends Controller {

    use HasBlogCategory;

    /**
     * @return Application|Factory|View
     */
    public function index(string $slug = '')
    {
        $item = app(BlogCategoryRepository::class)->findSlug($slug);

        $blogCategories = app(BlogCategoryRepository::class)->all($item->id ?? 0);

        $categoryPath = [];

        if ($item) {
            $blogPosts = $item
                ->posts()
                ->orderByDesc('created_at')
                ->paginate(10);

            $categoryPath = $this->getFullPathCategories($item);
        } else {
            $blogPosts = app(BlogPostRepository::class)->paginate(10);
        }

        return view('blog_category.index', compact(
            'item',
            'blogCategories',
            'blogPosts',
            'categoryPath'
        ));
    }

}
