<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
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
        $email = strtolower(substr($this->email, 0, 254));

        // replace the data ready for validation
        $this->merge([
            'email' => $email,
        ]);
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
            'name' => [
                "required",
                "string",
                "min:1",
                "max:31"
            ],
            'email' => [
                "required",
                "string",
                "max:254",
                "email",
                "unique:users,email," . Auth::User()->id
            ],
            'password' => [
                "nullable",
                "confirmed",
                Password::min(8)->uncompromised(),
            ],
            'rows' => [
                "required",
                "integer",
                "min:10",
                "max:100",
            ],
            'current_password' => [
                "required"
            ]
        ];
    }

}
