<?php

namespace App\Http\Requests\Admin\FAQ;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'question' => 'required|min:3|max:1000',
            'answer' => 'required|min:3',
            'faq_category_id' => 'required|exists:f_a_q_categories,id',
            'status' => 'integer',
            'sort_order' => 'integer',
        ];
    }
}
