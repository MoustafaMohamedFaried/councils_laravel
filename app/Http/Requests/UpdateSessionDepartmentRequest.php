<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSessionDepartmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    // public function rules(): array
    // {
    //     return [
    //         'department_id' => 'required',
    //         'place' => 'required',
    //         'start_time' => 'required|date',
    //         // 'start_time' => 'required|date_format:Y-m-d H:i:s',
    //         'decision_by' => 'required',
    //         'total_hours' => 'required',
    //         'agenda_id' => 'required',
    //         'user_id' => 'required',
    //     ];
    // }
    // public function messages(): array
    // {
    //     return [
    //         'department_id.required' => 'Department is required',
    //         'place.required' => 'Place is required',

    //         'start_time.required' => 'Start time is required',
    //         'start_time.date' => 'Start time must be datetime',

    //         'agenda_id.required' => 'Topic is required',
    //         'decision_by.required' => 'Decision_by is required',
    //         'total_hours.required' => 'Total hours is required',
    //         'user_id.required' => 'Invitations is required',
    //     ];
    // }
}
