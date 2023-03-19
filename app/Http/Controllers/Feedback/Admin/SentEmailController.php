<?php

namespace App\Http\Controllers\Feedback\Admin;

use App\Filters\Admin\Feedback\SentEmailFilter;
use App\Http\Controllers\Controller;
use App\Models\SentEmail;
use Illuminate\Http\Request;

class SentEmailController extends Controller
{

    public function index(SentEmailFilter $request)
    {
        $items = SentEmail::filter($request)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.sent_emails.index', compact('items'));
    }

}
