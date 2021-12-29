<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class ReportStoreRequest extends FormRequest
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
        // validate the form
        return [
            'found' => [
                "required",
                "string",
                "in:HSGOA|HSMOD|HSMEM|SEARCH|SCAN|WATCH|OTHER",
            ],
            'justification' => [
                "required",
                "string",
                "min:5",
                "max:64000"
            ],
            'username' => [
                "required",
                "string",
                "max:254"
            ],
            'email' => [
                "required",
                "string",
            ],
            'regions' => [
                "required",
                "string",
                "min:1",
                "max:254",
            ],
            'warnings' => [
                "required",
                "string",
                "min:1",
                "max:254",
            ],
        ];
    }

}
