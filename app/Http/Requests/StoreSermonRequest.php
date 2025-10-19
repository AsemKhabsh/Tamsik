<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Store Sermon Request
 * 
 * طلب التحقق من صحة بيانات إنشاء خطبة جديدة
 */
class StoreSermonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // التحقق من أن المستخدم مسجل دخول
        if (!auth()->check()) {
            return false;
        }

        // التحقق من أن المستخدم لديه صلاحية إنشاء خطب
        $allowedTypes = ['admin', 'preacher', 'scholar', 'data_entry'];
        return in_array(auth()->user()->user_type, $allowedTypes);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $categories = array_keys(config('categories.sermons'));
        $difficultyLevels = array_keys(config('categories.difficulty_levels'));
        $occasions = array_keys(config('categories.occasions'));

        return [
            // الحقول الأساسية
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:100',
            'category' => 'required|string|in:' . implode(',', $categories),
            
            // الحقول الاختيارية
            'introduction' => 'nullable|string|max:1000',
            'main_content' => 'nullable|string',
            'conclusion' => 'nullable|string|max:1000',
            
            // التصنيف والوسوم
            'tags' => 'nullable|string|max:500',
            'occasion' => 'nullable|string|in:' . implode(',', $occasions),
            
            // معلومات إضافية
            'sermon_date' => 'nullable|date',
            'target_audience' => 'nullable|string|max:255',
            'difficulty_level' => 'nullable|string|in:' . implode(',', $difficultyLevels),
            
            // المراجع
            'references' => 'nullable|string|max:2000',
            
            // الملفات
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'audio_file' => 'nullable|mimes:mp3,wav,m4a|max:20480',
            'video_file' => 'nullable|mimes:mp4,avi,mov|max:51200',
            
            // الحالة
            'is_published' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // رسائل الحقول الأساسية
            'title.required' => 'عنوان الخطبة مطلوب',
            'title.max' => 'عنوان الخطبة يجب ألا يتجاوز 255 حرف',
            
            'content.required' => 'محتوى الخطبة مطلوب',
            'content.min' => 'محتوى الخطبة يجب أن يكون 100 حرف على الأقل',
            
            'category.required' => 'تصنيف الخطبة مطلوب',
            'category.in' => 'التصنيف المحدد غير صحيح',
            
            // رسائل الحقول الاختيارية
            'introduction.max' => 'المقدمة يجب ألا تتجاوز 1000 حرف',
            'conclusion.max' => 'الخاتمة يجب ألا تتجاوز 1000 حرف',
            
            'tags.max' => 'الوسوم يجب ألا تتجاوز 500 حرف',
            'occasion.in' => 'المناسبة المحددة غير صحيحة',
            
            'sermon_date.date' => 'تاريخ الخطبة غير صحيح',
            
            'target_audience.max' => 'الجمهور المستهدف يجب ألا يتجاوز 255 حرف',
            'difficulty_level.in' => 'مستوى الصعوبة المحدد غير صحيح',
            
            'references.max' => 'المراجع يجب ألا تتجاوز 2000 حرف',
            
            // رسائل الملفات
            'image.image' => 'الملف المرفوع يجب أن يكون صورة',
            'image.mimes' => 'الصورة يجب أن تكون من نوع: jpeg, png, jpg, webp',
            'image.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت',
            
            'audio_file.mimes' => 'الملف الصوتي يجب أن يكون من نوع: mp3, wav, m4a',
            'audio_file.max' => 'حجم الملف الصوتي يجب ألا يتجاوز 20 ميجابايت',
            
            'video_file.mimes' => 'ملف الفيديو يجب أن يكون من نوع: mp4, avi, mov',
            'video_file.max' => 'حجم ملف الفيديو يجب ألا يتجاوز 50 ميجابايت',
            
            // رسائل الحالة
            'is_published.boolean' => 'حالة النشر يجب أن تكون صحيحة أو خاطئة',
            'is_featured.boolean' => 'حالة الإبراز يجب أن تكون صحيحة أو خاطئة',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title' => 'عنوان الخطبة',
            'content' => 'محتوى الخطبة',
            'category' => 'التصنيف',
            'introduction' => 'المقدمة',
            'main_content' => 'المحتوى الرئيسي',
            'conclusion' => 'الخاتمة',
            'tags' => 'الوسوم',
            'occasion' => 'المناسبة',
            'sermon_date' => 'تاريخ الخطبة',
            'target_audience' => 'الجمهور المستهدف',
            'difficulty_level' => 'مستوى الصعوبة',
            'references' => 'المراجع',
            'image' => 'الصورة',
            'audio_file' => 'الملف الصوتي',
            'video_file' => 'ملف الفيديو',
            'is_published' => 'حالة النشر',
            'is_featured' => 'حالة الإبراز',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // تحويل الوسوم من نص إلى مصفوفة إذا لزم الأمر
        if ($this->has('tags') && is_string($this->tags)) {
            $this->merge([
                'tags' => $this->tags,
            ]);
        }

        // تحويل المراجع من نص إلى مصفوفة إذا لزم الأمر
        if ($this->has('references') && is_string($this->references)) {
            $this->merge([
                'references' => $this->references,
            ]);
        }
    }
}

