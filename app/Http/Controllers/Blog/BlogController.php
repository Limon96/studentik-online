<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Repositories\BlogCategoryRepository;
use App\Repositories\BlogPostRepository;
use App\Traits\HasBlogCategory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Crypt;

class BlogController extends Controller {


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

    /**
     * @param string $slug
     * @return Application|Factory|View
     */
    public function show(string $slug)
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

    public function preview(string $slug)
    {
        $blogPostRepository = app(BlogPostRepository::class);

        $item = $blogPostRepository->findSlugByPreview($slug);

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
