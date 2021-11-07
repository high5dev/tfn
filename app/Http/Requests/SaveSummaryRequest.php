<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class SaveSummaryRequest extends FormRequest
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
            'zaps' => [
                "required",
                "integer",
                "min:0",
                "max:999"
            ],
            'notes' => [
                "required",
                "string",
                "min:3",
                "max:16384",
            ],
        ];
    }
}
