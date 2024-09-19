<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTopicAgendaRequest extends FormRequest
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
            'department_id' => 'required',
            'faculty_id' => 'required',
            'main_topic' => 'required',
            'topic_id' => 'required',
        ];
    }
    public function messages(): array
    {
        return [
            'department_id.required' => 'Department is required',
            'faculty_id.required' => 'Faculty is required',
            'main_topic.required' => 'Main topic is required',
            'topic_id.required' => 'Sup topic is required',
        ];
    }
}
