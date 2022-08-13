<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $title = 'Студентик – сервис консультации и сопровождения студентов в учёбе';
        $description = 'Консультация, подбор литературы и другая помощь по всем учебным дисциплинам';
        $keywords = '';
        $current_url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        $domain = $_SERVER['SERVER_NAME'];

        return view('welcome', compact(
            'title',
            'description',
            'keywords',
            'current_url',
            'domain'
        ));
    }
}
