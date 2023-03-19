<?php

namespace App\Observers;

use App\Models\Landing;
use Illuminate\Support\Str;

class LandingObserver
{

    public function created(Landing $landing)
    {
        //
    }
    public function creating(Landing $landing)
    {
        $this->serializeComponents($landing);
        $this->setSlug($landing);
        $this->setMenuTitle($landing);
    }

    /**
     * Handle the Landing "updated" event.
     *
     * @param  \App\Models\Landing  $landing
     * @return void
     */
    public function updated(Landing $landing)
    {
        //
    }


    public function updating(Landing $landing)
    {
        $this->serializeComponents($landing);
        $this->setSlug($landing);
        $this->setMenuTitle($landing);
    }

    /**
     * Handle the Landing "deleted" event.
     *
     * @param  \App\Models\Landing  $landing
     * @return void
     */
    public function deleted(Landing $landing)
    {
        //
    }

    /**
     * Handle the Landing "restored" event.
     *
     * @param  \App\Models\Landing  $landing
     * @return void
     */
    public function restored(Landing $landing)
    {
        //
    }

    /**
     * Handle the Landing "force deleted" event.
     *
     * @param  \App\Models\Landing  $landing
     * @return void
     */
    public function forceDeleted(Landing $landing)
    {
        //
    }

    private function serializeComponents(Landing $landing)
    {
        $landing->components = json_encode($landing->components);
    }

    private function setSlug(Landing $landing)
    {
        if (empty($landing->slug)) {
            $landing->slug = Str::slug($landing->title);
        }
    }

    private function setMenuTitle(Landing $landing)
    {
        if (empty($landing->menu_title)) {
            $landing->menu_title = $landing->title;
        }
    }

}
