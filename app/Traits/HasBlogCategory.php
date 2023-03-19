<?php

namespace App\Traits;


use App\Models\BlogCategory;

trait HasBlogCategory
{

    protected function getFullPathCategories(BlogCategory $category)
    {
        $fullPath = [ $category ];

        while ($category->parent) {
            if ($category->parent) {
                $category = $category->parent;
                $fullPath[] = $category;
            }
        }

        return array_reverse($fullPath);
    }

}
