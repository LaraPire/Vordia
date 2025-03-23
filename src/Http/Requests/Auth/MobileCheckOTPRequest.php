<?php

namespace Rayiumir\Vordia\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class MobileCheckOTPRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'otp' => 'required|digits:6',
            'login_token' => 'required'
        ];
    }
}

