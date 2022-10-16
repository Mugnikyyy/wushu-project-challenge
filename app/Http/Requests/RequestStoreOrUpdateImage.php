<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestStoreOrUpdateImage extends FormRequest
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
        $rules = [
            'title' => 'nullable',
            'media' => '|mimes:png,jpg',
            'parent_id' => 'nullable',
        ];

        if($this->isMethod('POST')){
            $rules['media'] .= '|required';
        }

        return $rules;
    }
}
