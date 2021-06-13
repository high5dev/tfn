<?php

namespace App\Http\Requests\Admin;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class AdminUserUpdateRequest extends FormRequest
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
        //
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
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
                    "unique:users,email," . $this->id
                ],
                'password' => [
                    "nullable",
                    "confirmed",
                    "nist_password"
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
