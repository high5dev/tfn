<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class saveSummaryRequest extends FormRequest
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
        $type = strtoupper(substr($this->type, 0, 7));
        $theword = strtolower(substr($this->theword, 0, 254));

        // replace the data ready for validation
        $this->merge([
            'type' => $type,
            'theword' => $theword
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
            'zaps' => [
                "required",
                "integer",
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
