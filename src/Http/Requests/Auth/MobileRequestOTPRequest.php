<?php

namespace Rayiumir\Vordia\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Rayiumir\Vordia\Rules\ValidateMobile;

class MobileRequestOTPRequest extends FormRequest
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
            'mobile' => ['required', new ValidateMobile()]
        ];
    }
}

