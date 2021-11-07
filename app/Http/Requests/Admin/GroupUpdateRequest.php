<?php

namespace App\Http\Requests\Admin;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class GroupUpdateRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return TRUE;
    }

    /**
     * Pre-process the Request
     */
    public function prepareForValidation(): void
    {
        $link = strtolower(substr($this->link, 0, 255));
        $url = strtolower(substr($this->url, 0, 255));

        // replace the data ready for validation
        $this->merge([
            'link' => $link,
            'url' => $url
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'link' => [
                "required",
                "string",
                "min:11",
                "max:255"
            ],
            'name' => [
                "required",
                "string",
                "min:1"
            ],
            'goa' => [
                "required",
                "string",
                "min:1"
            ],
            'region' => [
                "required",
                "string",
                "min:1"
            ],
            'country' => [
                "required",
                "string",
                "min:1"
            ],
            'url' => [
                "required",
                "string",
                "min:11",
            ],
            'contact' => [
                "required",
                "string",
                "min:1"
            ],
            'admin_password' => [
                "required",
                "string"
            ]
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
//    public function messages()
//    {
//        return [
//            'user_id.required' => 'You must select an owner'
//        ];
//    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
//    public function attributes()
//    {
//        return [
//            'destination' => 'destination number',
//        ];
//    }
}
