<?php

namespace App\Http\Requests\Admin;

use Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class ScansUpdateRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'started' => [
                "required",
                "datetime",
            ],
            'stopped' => [
                "required",
                "datetime",
            ],
            'startid' => [
                "required",
                "integer",
            ],
            'stopid' => [
                "required",
                "integer",
            ],
            'startts' => [
                "required",
                "datetime",
            ],
            'stoppts' => [
                "required",
                "datetime",
            ],
            'zaps' => [
                "required",
                "integer",
                "min:0",
                "max:999",
            ],
            'notes' => [
                "required",
                "string",
                "min:10",
                "max:65535",
            ],
            'admin_password' => [
                "required",
                "string"
            ]
        ];
    }

}
