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
            "date_end" => "required",
            "date_unknown" => "",
            "description" => "required|string|min:3",
            "premium" => "",
            "hot" => "",
            "plagiarism_check_unknown" => "",
            "plagiarism_check_id" => "",
            "plagiarism" => "",
            "price" => "",
            "attachment" => "",
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Название должно содержать от 3 до 150 символов',
            'work_type.required' => 'Выберите тип работы',
            'work_type.exists' => 'Выберите тип работы',
            'subject.required' => 'Выберите предмет',
            'subject.exists' => 'Выберите предмет',
            'date_end.required' => 'Выберите дату завершения задачи',
            'description.required' => 'Описание не может быть пустым и должно содержать больше 3 символов',
            'description.string' => 'Описание не может быть пустым и должно содержать больше 3 символов',
            'description.min' => 'Описание не может быть пустым и должно содержать больше 3 символов',
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'название',
            'work_type' => 'типа работы',
            'subject' => 'предмета работы',
        ];
    }
}
