<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepartmentRequest extends FormRequest
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
            'faculty_id' => 'required',
        ];
    }
    public function messages(): array
    {
        return [
            'ar_name.required' => 'Arabic name is required',
            'en_name.required' => 'English name is required',
            'faculty_id.required' => 'Faculty is required',
        ];
    }
}
