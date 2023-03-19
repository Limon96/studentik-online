<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CreateSessionController extends Controller
{

    public function __invoke()
    {
        session(['__autoload_session' => true]);

        return back();
    }

}
