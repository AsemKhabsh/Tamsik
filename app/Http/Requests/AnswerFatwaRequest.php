<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnswerFatwaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->user_type, ['admin', 'scholar']);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'answer' => 'required|string|min:50',
            'references' => 'nullable|array',
            'references.*' => 'string',
            'is_published' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'answer.required' => 'نص الإجابة مطلوب',
            'answer.min' => 'الإجابة يجب أن تكون على الأقل 50 حرفاً',
            'references.array' => 'المراجع يجب أن تكون مصفوفة',
        ];
    }
}

