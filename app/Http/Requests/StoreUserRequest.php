<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required',
            'headquarter_id' => 'required',
            'faculty_id' => 'required',
            'position_id' => 'required',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'headquarter_id.required' => 'Headquarter is required',
            'position_id.required' => 'Position is required',
            'faculty_id.required' => 'Faculty is required',
        ];
    }
}
