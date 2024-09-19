<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTopicRequest extends FormRequest
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
            'title' => 'required|unique:topics,title',
            'main_topic_id' => 'nullable',
        ];
    }
    public function messages(): array
    {
        return [
            'title.required' => 'Title is required',
            'title.unique' => 'Title must be unique',
            // 'main_topic_id.required' => 'Headquarter is required',
        ];
    }
}
