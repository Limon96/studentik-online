<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Repositories\BlogPostRepository;
use App\Traits\HasBlogCategory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class BlogController extends Controller {

    use HasBlogCategory;

    /**
     * @param string $slug
     * @return Application|Factory|View
     */
    public function index(string $slug)
    {
        $blogPostRepository = app(BlogPostRepository::class);

        $item = $blogPostRepository->findSlug($slug);

        if (!$item) {
            return abort(404);
        }

        $categoryPath = $this->getFullPathCategories($item->category);

        $item->addView();

        $popularPosts = $blogPostRepository->popular(5, $item->id);

        return view('blog.index', compact(
            'item',
            'categoryPath',
            'popularPosts'
        ));
    }


}
