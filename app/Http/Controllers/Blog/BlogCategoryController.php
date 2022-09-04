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
        $blogCategoryRepository = app(BlogCategoryRepository::class);
        $blogPostRepository = app(BlogPostRepository::class);

        $item = $blogCategoryRepository->findSlug($slug);

        $blogCategories = $blogCategoryRepository->all($item->id ?? 0);

        $blogCategoriesIds = [];
        $categoryPath = [];

        if ($item) {
            $blogCategoriesIds = $blogCategoryRepository->getChildrenIds($item);

            $categoryPath = $this->getFullPathCategories($item);
            $blogPosts = $blogPostRepository->fromCategory($blogCategoriesIds, 10);
        } else {
            $blogPosts = $blogPostRepository->paginate(10);
        }


        return view('blog_category.index', compact(
            'item',
            'blogCategories',
            'blogPosts',
            'categoryPath'
        ));
    }

}
