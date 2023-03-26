<?php

namespace App\Http\Controllers\Landing;

use App\Components\PageBuilder\PageBuilder;
use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Customer;
use App\Models\Landing;
use App\Models\Order;
use App\Models\Subject;
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

        $totals = new \stdClass();
        $totals->students = Customer::where('status', 1)->where('customer_group_id', '1')->count();
        $totals->experts = Customer::where('status', 1)->where('customer_group_id', '2')->count();
        $totals->order_completed = Order::where('order_status_id', '6')->count();

        $subjects = $this->getSubjects();

        $blogPosts = BlogPost::orderByDesc('created_at')->limit(8)->get();

        return view('landing.show',
            compact(
                'item',
                'sections',
                'type_work_pages',
                'subject_pages',
                'totals',
                'subjects',
                'blogPosts',
            )
        );
    }

    public function subject($work_type_slug, $subject_slug)
    {
        $parentItem = app(LandingRepository::class)
            ->getBySlug($work_type_slug);
        $item = app(LandingRepository::class)
            ->getBySlug($subject_slug);

        if (is_null($item)) {
            return redirect('https://studentik.online/not_found');
        }

        $sections = app(SectionRepository::class)->getForSelect();
        $type_work_pages = app(LandingRepository::class)->getTypeWorkPages();
        $subject_pages = app(LandingRepository::class)->getSubjectPages();

        $totals = new \stdClass();
        $totals->students = Customer::where('customer_group_id', 1)->count();
        $totals->experts = Customer::where('customer_group_id', 2)->count();
        $totals->order_completed = Order::where('order_status_id', 6)->count();

        $subjects = $this->getSubjects();

        return view('landing.show',
            compact(
                'item',
                'sections',
                'type_work_pages',
                'subject_pages',
                'totals',
                'subjects',
            )
        );
    }
    protected function getSubjects()
    {
        $subjects = Subject
            ::orderBy('name')
            ->get();

        $results = [];

        foreach ($subjects as $subject) {
            $key = mb_substr($subject->name, 0, 1);
            $results[$key][] = $subject;
        }

        return $results;
    }

}
