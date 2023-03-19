<?php

namespace App\Http\Controllers\FAQ;

use App\Http\Controllers\Controller;
use App\Repositories\FAQCategoryRepository;
use App\Repositories\FAQRepository;
use Illuminate\Http\Request;

class FAQController extends Controller
{

    public function index()
    {
        $items = app(FAQCategoryRepository::class)->all();
        $lastUpdated = app(FAQRepository::class)->lastUpdated();

        return view('faq.index', compact(
            'items',
            'lastUpdated'
        ));
    }

}
