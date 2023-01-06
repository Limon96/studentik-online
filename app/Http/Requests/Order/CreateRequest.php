<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "payment_blocking_id" => "required|exists:payment_blocking,payment_blocking_id",
            "title" => "required|string|min:3|max:255",
            "work_type" => "required|exists:work_type,work_type_id",
            "subject" => "required|exists:subjects,id",
            "date_end" => "",
            "description" => "",
            "premium" => "",
            "hot" => "",
            "plagiarism_check_unknown" => "",
            "plagiarism_check_id" => "",
            "plagiarism" => "",
            "price" => "",
        ];
    }
}
