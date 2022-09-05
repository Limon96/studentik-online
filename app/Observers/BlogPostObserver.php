<?php

namespace App\Observers;

use App\Components\Image\Image;
use App\Models\BlogPost;
use Illuminate\Support\Str;

class BlogPostObserver
{

    public function created(BlogPost $blogPost)
    {
        //
    }
    public function creating(BlogPost $blogPost)
    {
        $this->setImage($blogPost);
        $this->setSlug($blogPost);
        $this->processingText($blogPost);
    }

    /**
     * Handle the BlogPost "updated" event.
     *
     * @param  \App\Models\BlogPost  $blogPost
     * @return void
     */
    public function updated(BlogPost $blogPost)
    {
        //
    }


    public function updating(BlogPost $blogPost)
    {
        $this->setImage($blogPost);
        $this->setSlug($blogPost);
        $this->processingText($blogPost);
    }

    /**
     * Handle the BlogPost "deleted" event.
     *
     * @param  \App\Models\BlogPost  $blogPost
     * @return void
     */
    public function deleted(BlogPost $blogPost)
    {
        //
    }

    /**
     * Handle the BlogPost "restored" event.
     *
     * @param  \App\Models\BlogPost  $blogPost
     * @return void
     */
    public function restored(BlogPost $blogPost)
    {
        //
    }

    /**
     * Handle the BlogPost "force deleted" event.
     *
     * @param  \App\Models\BlogPost  $blogPost
     * @return void
     */
    public function forceDeleted(BlogPost $blogPost)
    {
        //
    }

    private function setSlug(BlogPost $blogPost)
    {
        if (empty($blogPost->slug)) {
            $blogPost->slug = Str::slug($blogPost->title);
        }
    }

    protected function setImage(BlogPost $blogPost)
    {
        $file = request()->file('image');

        if ($file) { // был загружен файл изображения
            $path = $file->store('blog/images', 'public');
            $blogPost->image = 'storage/' . $path;
        }

        if (empty($blogPost->image)) {
            $blogPost->image = '';
        }
    }



    protected function processingText(BlogPost $blogPost)
    {
        if (!isset($blogPost->text)) {
            $blogPost->text = '';
        }

        $blogPost->text = Image::replaceBase64FromHTML($blogPost->text);
    }

}
