<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLectureRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->user_type, ['admin', 'scholar', 'lecturer']);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'topic' => 'nullable|string|max:255',
            'lecture_date' => 'required|date',
            'duration' => 'nullable|integer|min:1',
            'location' => 'nullable|string|max:255',
            'video_url' => 'nullable|url',
            'audio_url' => 'nullable|url',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'عنوان المحاضرة مطلوب',
            'title.max' => 'عنوان المحاضرة يجب ألا يتجاوز 255 حرف',
            'description.required' => 'وصف المحاضرة مطلوب',
            'lecture_date.required' => 'تاريخ المحاضرة مطلوب',
            'lecture_date.date' => 'تاريخ المحاضرة غير صحيح',
            'duration.integer' => 'مدة المحاضرة يجب أن تكون رقماً',
            'duration.min' => 'مدة المحاضرة يجب أن تكون على الأقل دقيقة واحدة',
            'video_url.url' => 'رابط الفيديو غير صحيح',
            'audio_url.url' => 'رابط الصوت غير صحيح',
        ];
    }
}

