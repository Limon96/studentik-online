<?php

namespace App\Http\Controllers\Autocomplete;

use App\Http\Controllers\Controller;
use App\Http\Requests\Autocomplete\SubjectRequest;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    public function __invoke(SubjectRequest $request)
    {
        $subjects = Subject::where('section_id', $request->filter_section_id)
            ->select(DB::raw('id AS subject_id'), 'id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json([
            'subject' => $subjects
        ]);
    }
}
