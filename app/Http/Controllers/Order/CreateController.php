<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CreateRequest;
use App\Repositories\PlagiarismCheckRepository;
use App\Repositories\SectionRepository;
use App\Repositories\WorkTypeRepository;
use Illuminate\Http\Request;

class CreateController extends Controller
{

    public function showForm()
    {
        $sections = app(SectionRepository::class)->getForSelect();
        $work_types = app(WorkTypeRepository::class)->getForSelect();
        $plagiarism_checks = app(PlagiarismCheckRepository::class)->getForSelect();

        return view('order.form', compact(
            'sections',
            'work_types',
            'plagiarism_checks'
        ));
    }

    public function create(CreateRequest $request)
    {
        //dd($request->all(), $request->validated());
    }

}
