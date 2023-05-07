<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoditelCreateRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'FIO' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'FIO.required'=>'ФИО обязательно для заполнения',
            'FIO.unique'=>'ФИО должно быть уникальным',
        ];
    }

}
