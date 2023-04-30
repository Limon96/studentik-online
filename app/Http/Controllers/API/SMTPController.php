<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SMTPRequest;
use App\Mail\SMTP;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class SMTPController extends Controller
{

    /**
     * @param SMTPRequest $request
     * @return JsonResponse
     */
    public function send(SMTPRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();

        Mail::to($data['to'])->send(new SMTP(
            $data['subject'],
            $data['message'],
        ));

        return response()
            ->json([
                'success' => 1
            ]);
    }
}
