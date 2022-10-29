<?php

namespace App\Http\Requests\Admin\Newsletter;

use Illuminate\Foundation\Http\FormRequest;

class NewsletterSendRequest extends FormRequest
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
            'email' => '',
            'lack_activity' => '',
            'customer_group_id' => '',
            'template' => '',
            'subject' => 'required|min:3|max:256',
            'body' => '',
        ];
    }
}
