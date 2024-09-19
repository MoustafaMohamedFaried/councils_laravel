<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FacultyCouncilRequest extends FormRequest
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
            'dean_of_college' => 'required',
            'secretary_of_college_council' => 'required',
            'members' => 'required',
            'faculty_id' => 'required',
        ];
    }
    public function messages(): array
    {
        return [
            'dean_of_college.required' => 'Dean name is required',
            'secretary_of_college_council.required' => 'Secretary name is required',
            'members.required' => 'Members name is required',
            'faculty_id.required' => 'Faculty is required',
        ];
    }
}
