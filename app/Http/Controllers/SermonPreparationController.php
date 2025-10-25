<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sermon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SermonPreparationController extends Controller
{
    /**
     * عرض صفحة إعداد خطبة جديدة
     */
    public function create()
    {
        $user = Auth::user();

        // التحقق من تسجيل الدخول
        if (!$user) {
            return redirect()->route('login')->with('error', 'يجب تسجيل الدخول أولاً');
        }

        // التحقق من صلاحية المستخدم باستخدام Spatie Roles
        if (!$user->hasAnyRole(['preacher', 'scholar', 'admin', 'thinker', 'data_entry'])) {
            return redirect()->route('home')->with('error', 'هذه الصفحة مخصصة للخطباء والعلماء فقط');
        }

        // التصنيفات المتاحة
        $categories = [
            'faith' => 'العقيدة',
            'worship' => 'العبادات',
            'transactions' => 'المعاملات',
            'ethics' => 'الأخلاق والآداب',
            'family' => 'الأسرة والمجتمع',
            'contemporary' => 'القضايا المعاصرة',
            'biography' => 'السيرة النبوية',
            'quran' => 'علوم القرآن',
            'hadith' => 'علوم الحديث',
            'other' => 'أخرى'
        ];

        return view('sermons.prepare', compact('user', 'categories'));
    }

    /**
     * حفظ خطبة جديدة
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // التحقق من صلاحية المستخدم باستخدام Spatie Roles
        if (!$user->hasAnyRole(['admin', 'preacher', 'scholar'])) {
            return redirect()->route('home')->with('error', 'غير مصرح لك بإنشاء خطب');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'sermon_date' => 'nullable|date',
            'occasion' => 'nullable|string|max:100',
            'introduction' => 'required|string',
            'main_content' => 'required|string',
            'conclusion' => 'required|string',
            // حقول المقدمة
            'intro_topic' => 'nullable|string',
            'intro_evidence' => 'nullable|string',
            'intro_idea' => 'nullable|string',
            'intro_story' => 'nullable|string',
            'intro_connection' => 'nullable|string',
            // حقول الخطبة الأولى
            'first_sermon_element1' => 'nullable|string',
            'first_sermon_element1_content' => 'nullable|string',
            'first_sermon_element2' => 'nullable|string',
            'first_sermon_element2_content' => 'nullable|string',
            'first_sermon_element3' => 'nullable|string',
            'first_sermon_element3_content' => 'nullable|string',
            // حقول الخطبة الثانية
            'second_sermon_element1' => 'nullable|string',
            'second_sermon_element1_content' => 'nullable|string',
            'second_sermon_element2' => 'nullable|string',
            'second_sermon_element2_content' => 'nullable|string',
            // حقول المراجع
            'quran_verses' => 'nullable|string',
            'hadiths' => 'nullable|string',
            'scholars_quotes' => 'nullable|string',
            'references' => 'nullable|string',
            // حقول التفاصيل الإضافية
            'objectives' => 'nullable|string',
            'target_audience' => 'nullable|string',
            'duration' => 'nullable|integer|min:5|max:120',
            'notes' => 'nullable|string',
        ]);

        $sermon = new Sermon();
        $sermon->title = $request->title;
        $sermon->slug = \Str::slug($request->title);
        $sermon->sermon_date = $request->sermon_date;
        $sermon->occasion = $request->occasion;
        $sermon->introduction = $request->introduction;
        $sermon->main_content = $request->main_content;
        $sermon->conclusion = $request->conclusion;
        $sermon->author_id = $user->id;
        $sermon->status = 'draft';
        $sermon->is_published = false;

        // حفظ البيانات الإضافية في حقل metadata كـ JSON
        $metadata = [
            'intro' => [
                'topic' => $request->intro_topic,
                'evidence' => $request->intro_evidence,
                'idea' => $request->intro_idea,
                'story' => $request->intro_story,
                'connection' => $request->intro_connection,
            ],
            'first_sermon' => [
                'element1' => ['title' => $request->first_sermon_element1, 'content' => $request->first_sermon_element1_content],
                'element2' => ['title' => $request->first_sermon_element2, 'content' => $request->first_sermon_element2_content],
                'element3' => ['title' => $request->first_sermon_element3, 'content' => $request->first_sermon_element3_content],
            ],
            'second_sermon' => [
                'element1' => ['title' => $request->second_sermon_element1, 'content' => $request->second_sermon_element1_content],
                'element2' => ['title' => $request->second_sermon_element2, 'content' => $request->second_sermon_element2_content],
            ],
            'references' => [
                'quran_verses' => $request->quran_verses,
                'hadiths' => $request->hadiths,
                'scholars_quotes' => $request->scholars_quotes,
                'other_references' => $request->references,
            ],
            'details' => [
                'objectives' => $request->objectives,
                'target_audience' => $request->target_audience,
                'duration' => $request->duration,
                'notes' => $request->notes,
            ],
        ];

        $sermon->metadata = json_encode($metadata);

        $sermon->save();

        return redirect()->route('sermon.my')->with('success', 'تم إنشاء الخطبة بنجاح! ستتم مراجعتها من قبل الإدارة قبل النشر.');
    }

    /**
     * عرض خطب المستخدم الحالي
     */
    public function mySermons()
    {
        $user = Auth::user();

        // التحقق من صلاحية المستخدم باستخدام Spatie Roles
        if (!$user->hasAnyRole(['admin', 'preacher', 'scholar'])) {
            return redirect()->route('home')->with('error', 'غير مصرح لك بالوصول لهذه الصفحة');
        }

        $sermons = Sermon::where('author_id', $user->id)
                         ->orderBy('created_at', 'desc')
                         ->paginate(10);

        return view('sermons.my-sermons', compact('sermons', 'user'));
    }

    /**
     * تعديل خطبة للمستخدم الحالي
     */
    public function edit($id)
    {
        $user = Auth::user();
        $sermon = Sermon::where('author_id', $user->id)->findOrFail($id);

        // التحقق من صلاحية المستخدم باستخدام Spatie Roles
        if (!$user->hasAnyRole(['admin', 'preacher', 'scholar'])) {
            return redirect()->route('home')->with('error', 'غير مصرح لك بتعديل هذه الخطبة');
        }

        return view('sermons.edit-original', compact('sermon', 'user'));
    }

    /**
     * تحديث خطبة للمستخدم الحالي
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $sermon = Sermon::where('author_id', $user->id)->findOrFail($id);

        // التحقق من صلاحية المستخدم باستخدام Spatie Roles
        if (!$user->hasAnyRole(['admin', 'preacher', 'scholar'])) {
            return redirect()->route('home')->with('error', 'غير مصرح لك بتعديل هذه الخطبة');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'introduction' => 'required|string',
            'main_content' => 'required|string',
            'conclusion' => 'required|string',
            'references' => 'nullable|string',
            'tags' => 'nullable|string',
            'audio_file' => 'nullable|file|mimes:mp3,wav,m4a|max:51200',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $sermon->title = $request->title;
        $sermon->slug = \Str::slug($request->title);
        $sermon->category = $request->category;
        $sermon->introduction = $request->introduction;
        $sermon->main_content = $request->main_content;
        $sermon->conclusion = $request->conclusion;
        $sermon->references = $request->references;
        $sermon->status = 'draft'; // إعادة للمسودة عند التعديل
        
        // معالجة التاجات
        if ($request->tags) {
            $tags = array_map('trim', explode(',', $request->tags));
            $sermon->tags = json_encode($tags);
        }

        // رفع الملف الصوتي الجديد
        if ($request->hasFile('audio_file')) {
            // حذف الملف القديم
            if ($sermon->audio_file && \Storage::disk('public')->exists($sermon->audio_file)) {
                \Storage::disk('public')->delete($sermon->audio_file);
            }
            $audioPath = $request->file('audio_file')->store('sermons/audio', 'public');
            $sermon->audio_file = $audioPath;
        }

        // رفع الصورة الجديدة
        if ($request->hasFile('featured_image')) {
            // حذف الصورة القديمة
            if ($sermon->featured_image && \Storage::disk('public')->exists($sermon->featured_image)) {
                \Storage::disk('public')->delete($sermon->featured_image);
            }
            $imagePath = $request->file('featured_image')->store('sermons/images', 'public');
            $sermon->featured_image = $imagePath;
        }

        $sermon->save();

        return redirect()->route('sermons.my')->with('success', 'تم تحديث الخطبة بنجاح!');
    }
}
