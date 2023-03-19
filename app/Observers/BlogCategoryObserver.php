<?php

namespace App\Observers;

use App\Models\BlogCategory;
use Illuminate\Support\Str;

class BlogCategoryObserver
{

    public function created(BlogCategory $blogCategory)
    {
        //
    }
    public function creating(BlogCategory $blogCategory)
    {
        $this->setImage($blogCategory);
        $this->setSlug($blogCategory);
        $this->setText($blogCategory);
    }

    /**
     * Handle the BlogCategory "updated" event.
     *
     * @param  \App\Models\BlogCategory  $blogCategory
     * @return void
     */
    public function updated(BlogCategory $blogCategory)
    {
        //
    }


    public function updating(BlogCategory $blogCategory)
    {
        $this->setImage($blogCategory);
        $this->setSlug($blogCategory);
        $this->setText($blogCategory);
    }

    /**
     * Handle the BlogCategory "deleted" event.
     *
     * @param  \App\Models\BlogCategory  $blogCategory
     * @return void
     */
    public function deleted(BlogCategory $blogCategory)
    {
        //
    }

    /**
     * Handle the BlogCategory "restored" event.
     *
     * @param  \App\Models\BlogCategory  $blogCategory
     * @return void
     */
    public function restored(BlogCategory $blogCategory)
    {
        //
    }

    /**
     * Handle the BlogCategory "force deleted" event.
     *
     * @param  \App\Models\BlogCategory  $blogCategory
     * @return void
     */
    public function forceDeleted(BlogCategory $blogCategory)
    {
        //
    }

    private function setSlug(BlogCategory $blogCategory)
    {
        if (empty($blogCategory->slug)) {
            $blogCategory->slug = Str::slug($blogCategory->title);
        }
    }

    private function setText(BlogCategory $blogCategory)
    {
        if (empty($blogCategory->text)) {
            $blogCategory->text = '';
        }
    }

    protected function setImage(BlogCategory $blogCategory)
    {
        $file = request()->file('image');

        if ($file) { // был загружен файл изображения
            $path = $file->store('blog/images', 'public');
            $blogCategory->image = 'storage/' . $path;
        }

        if (empty($blogCategory->image)) {
            $blogCategory->image = '';
        }
    }

}
