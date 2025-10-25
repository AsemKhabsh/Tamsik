<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lecture;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LectureManagementController extends Controller
{
    /**
     * عرض صفحة إضافة محاضرة جديدة
     */
    public function create()
    {
        $user = Auth::user();

        // التحقق من صلاحية المستخدم باستخدام Spatie
        if (!$user->hasAnyRole(['admin', 'preacher', 'scholar', 'data_entry']) && !$user->can('create_lectures')) {
            return redirect()->route('home')->with('error', 'هذه الصفحة مخصصة للخطباء والعلماء ومدخلي البيانات فقط');
        }

        return view('lectures.create', compact('user'));
    }

    /**
     * حفظ محاضرة جديدة
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // التحقق من صلاحية المستخدم باستخدام Spatie
        if (!$user->hasAnyRole(['admin', 'preacher', 'scholar', 'data_entry']) && !$user->can('create_lectures')) {
            return redirect()->route('home')->with('error', 'غير مصرح لك بإنشاء محاضرات');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'scheduled_at' => 'required|date|after:now',
            'duration' => 'required|integer|min:15|max:480', // من 15 دقيقة إلى 8 ساعات
            'location' => 'nullable|string|max:255',
            'online_link' => 'nullable|url',
            'category' => 'required|string|max:100',
            'target_audience' => 'nullable|string|max:255',
            'prerequisites' => 'nullable|string',
            'max_attendees' => 'nullable|integer|min:1',
            'is_free' => 'required|boolean',
            'price' => 'nullable|numeric|min:0',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $lecture = new Lecture();
        $lecture->title = $request->title;
        $lecture->description = $request->description;
        $lecture->scheduled_at = $request->scheduled_at;
        $lecture->duration = $request->duration;
        $lecture->location = $request->location;
        $lecture->online_link = $request->online_link;
        $lecture->category = $request->category;
        $lecture->target_audience = $request->target_audience;
        $lecture->prerequisites = $request->prerequisites;
        $lecture->max_attendees = $request->max_attendees;
        $lecture->is_free = $request->is_free;
        $lecture->price = $request->is_free ? 0 : $request->price;
        $lecture->speaker_id = $user->id;

        // تحديد حالة النشر حسب دور المستخدم
        if ($user->hasRole('admin') || $user->can('publish_lectures')) {
            $lecture->status = 'scheduled'; // منشور مباشرة
            $lecture->is_published = true;
        } else {
            $lecture->status = 'pending'; // بانتظار المراجعة
            $lecture->is_published = false;
        }

        // رفع الصورة المميزة
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('lectures/images', 'public');
            $lecture->featured_image = $imagePath;
        }

        $lecture->save();

        $message = $lecture->is_published
            ? 'تم إنشاء المحاضرة ونشرها بنجاح!'
            : 'تم إنشاء المحاضرة بنجاح! ستتم مراجعتها من قبل الإدارة قبل النشر.';

        return redirect()->route('lectures.my')->with('success', $message);
    }

    /**
     * عرض محاضرات المستخدم الحالي
     */
    public function myLectures()
    {
        $user = Auth::user();

        if (!$user->hasAnyRole(['admin', 'preacher', 'scholar', 'data_entry'])) {
            return redirect()->route('home')->with('error', 'غير مصرح لك بالوصول لهذه الصفحة');
        }

        $lectures = Lecture::where('speaker_id', $user->id)
                          ->orderBy('scheduled_at', 'desc')
                          ->paginate(10);

        return view('lectures.my-lectures', compact('lectures', 'user'));
    }

    /**
     * تعديل محاضرة للمستخدم الحالي
     */
    public function edit($id)
    {
        $user = Auth::user();
        $lecture = Lecture::where('speaker_id', $user->id)->findOrFail($id);

        if (!$user->hasAnyRole(['admin', 'preacher', 'scholar']) && !$user->can('edit_lectures')) {
            return redirect()->route('home')->with('error', 'غير مصرح لك بتعديل هذه المحاضرة');
        }

        return view('lectures.edit', compact('lecture', 'user'));
    }

    /**
     * تحديث محاضرة للمستخدم الحالي
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $lecture = Lecture::where('speaker_id', $user->id)->findOrFail($id);

        if (!$user->hasAnyRole(['admin', 'preacher', 'scholar']) && !$user->can('edit_lectures')) {
            return redirect()->route('home')->with('error', 'غير مصرح لك بتعديل هذه المحاضرة');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'scheduled_at' => 'required|date|after:now',
            'duration' => 'required|integer|min:15|max:480',
            'location' => 'nullable|string|max:255',
            'online_link' => 'nullable|url',
            'category' => 'required|string|max:100',
            'target_audience' => 'nullable|string|max:255',
            'prerequisites' => 'nullable|string',
            'max_attendees' => 'nullable|integer|min:1',
            'is_free' => 'required|boolean',
            'price' => 'nullable|numeric|min:0',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $lecture->title = $request->title;
        $lecture->description = $request->description;
        $lecture->scheduled_at = $request->scheduled_at;
        $lecture->duration = $request->duration;
        $lecture->location = $request->location;
        $lecture->online_link = $request->online_link;
        $lecture->category = $request->category;
        $lecture->target_audience = $request->target_audience;
        $lecture->prerequisites = $request->prerequisites;
        $lecture->max_attendees = $request->max_attendees;
        $lecture->is_free = $request->is_free;
        $lecture->price = $request->is_free ? 0 : $request->price;
        $lecture->status = 'pending'; // إعادة للمراجعة عند التعديل

        // رفع الصورة الجديدة
        if ($request->hasFile('featured_image')) {
            // حذف الصورة القديمة
            if ($lecture->featured_image && \Storage::disk('public')->exists($lecture->featured_image)) {
                \Storage::disk('public')->delete($lecture->featured_image);
            }
            $imagePath = $request->file('featured_image')->store('lectures/images', 'public');
            $lecture->featured_image = $imagePath;
        }

        $lecture->save();

        return redirect()->route('lectures.my')->with('success', 'تم تحديث المحاضرة بنجاح!');
    }

    /**
     * حذف محاضرة للمستخدم الحالي
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $lecture = Lecture::where('speaker_id', $user->id)->findOrFail($id);

        if (!in_array($user->user_type, ['admin', 'preacher', 'scholar'])) {
            return redirect()->route('home')->with('error', 'غير مصرح لك بحذف هذه المحاضرة');
        }

        // حذف الصورة المرفقة
        if ($lecture->featured_image && \Storage::disk('public')->exists($lecture->featured_image)) {
            \Storage::disk('public')->delete($lecture->featured_image);
        }

        $lecture->delete();

        return redirect()->route('lectures.my')->with('success', 'تم حذف المحاضرة بنجاح!');
    }
}
