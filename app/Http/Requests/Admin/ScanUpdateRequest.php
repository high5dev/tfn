<?php

namespace App\Http\Requests\Admin;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class ScanUpdateRequest extends FormRequest
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
                "date_format: Y-m-d H:i:s"
            ],
            'stopped' => [
                "required",
                "date_format: Y-m-d H:i:s"
            ],
            'startid' => [
                "required",
                "integer"
            ],
            'stopid' => [
                "required",
                "integer"
            ],
            'startts' => [
                "required",
                "date_format: Y-m-d H:i:s"
            ],
            'stopts' => [
                "required",
                "date_format: Y-m-d H:i:s"
            ],
            'zaps' => [
                "required",
                "integer",
                "min:0",
                "max:999"
            ],
            'notes' => [
                "required",
                "string",
                "min:10",
                "max:65535"
            ],
            'admin_password' => [
                "required",
                "string"
            ]
        ];
    }

}
