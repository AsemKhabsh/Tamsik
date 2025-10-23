<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFatwaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'question' => 'required|string',
            'category' => 'required|string|in:worship,transactions,family,contemporary,ethics,beliefs,jurisprudence,quran,hadith',
            'scholar_id' => 'nullable|exists:users,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'عنوان السؤال مطلوب',
            'title.max' => 'عنوان السؤال يجب ألا يتجاوز 255 حرف',
            'question.required' => 'نص السؤال مطلوب',
            'category.required' => 'التصنيف مطلوب',
            'category.in' => 'التصنيف المحدد غير صحيح',
            'scholar_id.exists' => 'العالم المحدد غير موجود',
        ];
    }
}

