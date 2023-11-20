<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateGruzootpravitelRequest extends FormRequest
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
        $id='';
        $id=request('current_gruzootpravitel_id');
       // return dd(request('current_gruzootpravitel_id'));
        if($id=='')
        {
            return [
                'nazvanie' => 'required',
                'INN' => 'unique:gruzootpravitels,INN|required',
            ];
        }
        else
        {
            return [
                'nazvanie' => 'required',
                'INN' => 'required|unique:gruzootpravitels,INN,' . $id,
            ];
        }

    }

}
