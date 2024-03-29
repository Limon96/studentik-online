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

class BlogController extends Controller {


    use HasBlogCategory;

    const POST_LIMIT = 15;

    /**
     * @return Application|Factory|View
     */
    public function index(string $slug = '')
    {
        $blogCategoryRepository = app(BlogCategoryRepository::class);
        $blogPostRepository = app(BlogPostRepository::class);

        $item = $blogCategoryRepository->findSlug($slug);

        if ($slug && !$item) {
            abort(404);
        }

        $blogCategories = $blogCategoryRepository->all($item->id ?? 0);

        $categoryPath = [];

        if ($item) {
            $blogCategoriesIds = $blogCategoryRepository->getChildrenIds($item);

            $categoryPath = $this->getFullPathCategories($item);

            $blogPosts = $blogPostRepository->fromCategory($blogCategoriesIds, self::POST_LIMIT);
        } else {
            $blogPosts = $blogPostRepository->paginate(self::POST_LIMIT);
        }

        $currentPage = request()->get('page', 1);

        return view('blog_category.index', compact(
            'item',
            'blogCategories',
            'blogPosts',
            'categoryPath',
            'currentPage',
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
