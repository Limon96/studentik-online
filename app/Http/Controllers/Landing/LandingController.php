<?php

namespace App\Http\Controllers\Landing;

use App\Components\PageBuilder\PageBuilder;
use App\Http\Controllers\Controller;
use App\Repositories\LandingRepository;
use App\Repositories\SectionRepository;
use Illuminate\Http\Request;

class LandingController extends Controller
{

    /**
     * @param $slug
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index($slug)
    {
        $item = app(LandingRepository::class)
            ->getBySlug($slug);

        if (is_null($item)) {
            return redirect('https://studentik.online/not_found');
        }

        $sections = app(SectionRepository::class)->getForSelect();
        $type_work_pages = app(LandingRepository::class)->getTypeWorkPages();
        $subject_pages = app(LandingRepository::class)->getSubjectPages();

        return view('landing.show',
            compact(
                'item',
                'sections',
                'type_work_pages',
                'subject_pages'
            )
        );
    }

}
