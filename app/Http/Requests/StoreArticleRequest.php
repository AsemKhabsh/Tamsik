<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Store Article Request
 * 
 * طلب التحقق من صحة بيانات إنشاء مقال جديد
 */
class StoreArticleRequest extends FormRequest
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

        // التحقق من أن المستخدم لديه صلاحية إنشاء مقالات
        $allowedTypes = ['admin', 'scholar', 'thinker', 'data_entry'];
        return in_array(auth()->user()->user_type, $allowedTypes);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // الحقول الأساسية
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:100',
            'excerpt' => 'nullable|string|max:500',
            
            // التصنيف
            'category_id' => 'nullable|exists:categories,id',
            
            // الوسوم
            'tags' => 'nullable|string|max:500',
            
            // الصورة المميزة - تحسين الأمان
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048|mimetypes:image/jpeg,image/png,image/jpg,image/webp',
            
            // SEO
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            
            // الحالة
            'status' => 'nullable|string|in:draft,pending,published,archived',
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
            'title.required' => 'عنوان المقال مطلوب',
            'title.max' => 'عنوان المقال يجب ألا يتجاوز 255 حرف',
            
            'content.required' => 'محتوى المقال مطلوب',
            'content.min' => 'محتوى المقال يجب أن يكون 100 حرف على الأقل',
            
            'excerpt.max' => 'الملخص يجب ألا يتجاوز 500 حرف',
            
            'category_id.exists' => 'التصنيف المحدد غير موجود',
            
            'tags.max' => 'الوسوم يجب ألا تتجاوز 500 حرف',
            
            'featured_image.image' => 'الملف المرفوع يجب أن يكون صورة',
            'featured_image.mimes' => 'الصورة يجب أن تكون من نوع: jpeg, png, jpg, webp',
            'featured_image.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت',
            
            'meta_title.max' => 'عنوان SEO يجب ألا يتجاوز 60 حرف',
            'meta_description.max' => 'وصف SEO يجب ألا يتجاوز 160 حرف',
            
            'status.in' => 'الحالة المحددة غير صحيحة',
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
            'title' => 'عنوان المقال',
            'content' => 'محتوى المقال',
            'excerpt' => 'الملخص',
            'category_id' => 'التصنيف',
            'tags' => 'الوسوم',
            'featured_image' => 'الصورة المميزة',
            'meta_title' => 'عنوان SEO',
            'meta_description' => 'وصف SEO',
            'status' => 'الحالة',
            'is_featured' => 'حالة الإبراز',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // التحقق من محتوى الصورة
            if ($this->hasFile('featured_image')) {
                $image = $this->file('featured_image');
                $imageInfo = @getimagesize($image->getRealPath());

                if ($imageInfo === false) {
                    $validator->errors()->add('featured_image', 'الملف المرفوع ليس صورة صالحة');
                }

                // التحقق من أن الملف ليس ملف تنفيذي
                $extension = strtolower($image->getClientOriginalExtension());
                $dangerousExtensions = ['exe', 'bat', 'cmd', 'sh', 'php', 'js', 'html'];

                if (in_array($extension, $dangerousExtensions)) {
                    $validator->errors()->add('featured_image', 'نوع الملف غير مسموح به');
                }
            }
        });
    }
}

