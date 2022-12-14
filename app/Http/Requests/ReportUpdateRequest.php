<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ReportUpdateRequest extends FormRequest
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
                Rule::in(['HSGOA', 'HSMOD', 'HSMEM', 'SEARCH', 'SCAN', 'WATCH', 'OTHER']),
            ],
            'item' => [
                "required",
                "string",
                "min:3",
                "max:254"
            ],
            'dated' => [
                "required",
                "string",
                "min:12",
                "max:63"
            ],
            'justification' => [
                "required",
                "string",
                "min:5",
                "max:64000"
            ],
            'warnings' => [
                "required",
                "string",
                "min:1",
                "max:254",
            ],
            'password' => [
                "required",
                "string",
            ],
        ];
    }

}
