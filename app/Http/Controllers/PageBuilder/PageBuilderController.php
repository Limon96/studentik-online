<?php

namespace App\Http\Controllers\PageBuilder;

use App\Components\PageBuilder\Library\PageBuilderComponent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageBuilderController extends Controller
{

    public function index($block)
    {
        $component = PageBuilderComponent::get($block);

        return view('pagebuilder.component', $component);
    }

}
