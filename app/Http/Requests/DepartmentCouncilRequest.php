<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentCouncilRequest extends FormRequest
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
            'head_of_department' => 'required',
            'secretary_of_department_council' => 'required',
            'members' => 'required',
            'department_id' => 'required',
        ];
    }
    public function messages(): array
    {
        return [
            'head_of_department.required' => 'Head of department name is required',
            'secretary_of_department_council.required' => 'Secretary name is required',
            'members.required' => 'Members name is required',
            'department_id.required' => 'Department is required',
        ];
    }
}
