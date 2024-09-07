<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFacultyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'en_name' => 'required',
            'ar_name' => 'required',
            'headquarter_id' => 'required',
        ];
    }
    public function messages(): array
    {
        return [
            'ar_name.required' => 'Arabic name is required',
            'en_name.required' => 'English name is required',
            'headquarter_id.required' => 'Headquarter is required',
        ];
    }
}
