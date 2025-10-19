<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sermon;
use App\Models\Lecture;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * عرض لوحة التحكم الرئيسية
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_sermons' => Sermon::count(),
            'total_lectures' => Lecture::count(),
            'total_articles' => Article::count() ?? 0,
            'pending_articles' => Article::where('status', 'pending')->count() ?? 0,
            'published_sermons' => Sermon::where('is_published', true)->count(),
            'draft_sermons' => Sermon::where('is_published', false)->count(),
            'recent_users' => User::latest()->take(5)->get(),
            'recent_sermons' => Sermon::latest()->take(5)->get(),
            'recent_lectures' => Lecture::latest()->take(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * إدارة المستخدمين
     */
    public function users()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,scholar,preacher,thinker,data_entry,member',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'user_type' => $request->role, // تعيين user_type ليطابق role
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.users')->with('success', 'تم إنشاء المستخدم بنجاح');
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,scholar,preacher,thinker,data_entry,member',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'user_type' => $request->role, // تعيين user_type ليطابق role
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.users')->with('success', 'تم تحديث المستخدم بنجاح');
    }

    public function deleteUser(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users')->with('error', 'لا يمكنك حذف حسابك الخاص');
        }

        $user->delete();
        return redirect()->route('admin.users')->with('success', 'تم حذف المستخدم بنجاح');
    }

    /**
     * إدارة الخطب
     */
    public function sermons()
    {
        $sermons = Sermon::with('author')->latest()->paginate(20);
        return view('admin.sermons.index', compact('sermons'));
    }



    public function editSermon(Sermon $sermon)
    {
        $authors = User::where('role', 'scholar')->orWhere('role', 'admin')->get();
        return view('admin.sermons.edit', compact('sermon', 'authors'));
    }

    public function updateSermon(Request $request, Sermon $sermon)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'introduction' => 'nullable|string',
            'content' => 'required|string',
            'conclusion' => 'nullable|string',
            'author_id' => 'required|exists:users,id',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'audio_file' => 'nullable|file|mimes:mp3,wav,m4a|max:50000',
        ]);

        $data = $request->all();
        $data['is_published'] = $request->has('is_published');
        $data['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('audio_file')) {
            // حذف الملف القديم
            if ($sermon->audio_file) {
                Storage::disk('public')->delete($sermon->audio_file);
            }
            $data['audio_file'] = $request->file('audio_file')->store('sermons/audio', 'public');
        }

        $sermon->update($data);

        return redirect()->route('admin.sermons')->with('success', 'تم تحديث الخطبة بنجاح');
    }

    public function deleteSermon(Sermon $sermon)
    {
        if ($sermon->audio_file) {
            Storage::disk('public')->delete($sermon->audio_file);
        }

        $sermon->delete();
        return redirect()->route('admin.sermons')->with('success', 'تم حذف الخطبة بنجاح');
    }

    /**
     * إدارة المحاضرات
     */
    public function lectures()
    {
        $lectures = Lecture::with('speaker')->latest()->paginate(20);
        return view('admin.lectures.index', compact('lectures'));
    }



    public function editLecture(Lecture $lecture)
    {
        $lecture->load('speaker'); // تحميل علاقة المحاضر
        $speakers = User::where('role', 'scholar')->orWhere('role', 'admin')->get();
        return view('admin.lectures.edit', compact('lecture', 'speakers'));
    }

    public function updateLecture(Request $request, Lecture $lecture)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'speaker' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'scheduled_at' => 'required|date',
            'duration' => 'nullable|integer|min:15|max:300',
            'capacity' => 'nullable|integer|min:10',
            'category' => 'nullable|string|max:255',
            'status' => 'required|in:scheduled,ongoing,completed,cancelled',
            'additional_info' => 'nullable|string',
        ]);

        // البحث عن المحاضر أو إنشاء واحد جديد
        $speaker = User::where('name', $request->speaker)->first();
        if (!$speaker) {
            // إنشاء مستخدم جديد للمحاضر
            $speaker = User::create([
                'name' => $request->speaker,
                'email' => strtolower(str_replace(' ', '.', $request->speaker)) . '@tamsik.com',
                'password' => Hash::make('password123'),
                'role' => 'scholar',
                'is_active' => true,
            ]);
        }

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'topic' => $request->title,
            'category' => $request->category ?? 'عام',
            'speaker_id' => $speaker->id,
            'location' => $request->location,
            'city' => 'صنعاء',
            'venue' => $request->location,
            'scheduled_at' => $request->scheduled_at,
            'duration' => $request->duration,
            'max_attendees' => $request->capacity,
            'requirements' => $request->additional_info,
            'target_audience' => 'general',
            'status' => $request->status,
            'is_published' => true,
        ];

        $lecture->update($data);

        return redirect()->route('admin.lectures')->with('success', 'تم تحديث المحاضرة بنجاح');
    }

    public function deleteLecture(Lecture $lecture)
    {
        if ($lecture->audio_file) {
            Storage::disk('public')->delete($lecture->audio_file);
        }

        $lecture->delete();
        return redirect()->route('admin.lectures')->with('success', 'تم حذف المحاضرة بنجاح');
    }

    /**
     * إدارة العلماء والمفكرين
     */
    public function scholars()
    {
        $scholars = User::where('role', 'scholar')->latest()->paginate(20);
        return view('admin.scholars.index', compact('scholars'));
    }

    public function createScholar()
    {
        return view('admin.scholars.create');
    }

    public function storeScholar(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'bio' => 'nullable|string',
            'title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['role'] = 'scholar';
        $data['password'] = Hash::make($request->password);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('scholars/images', 'public');
        }

        User::create($data);

        return redirect()->route('admin.scholars')->with('success', 'تم إنشاء العالم بنجاح');
    }

    public function editScholar(User $scholar)
    {
        return view('admin.scholars.edit', compact('scholar'));
    }

    public function updateScholar(Request $request, User $scholar)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $scholar->id,
            'bio' => 'nullable|string',
            'title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            // حذف الصورة القديمة
            if ($scholar->image) {
                Storage::disk('public')->delete($scholar->image);
            }
            $data['image'] = $request->file('image')->store('scholars/images', 'public');
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        $scholar->update($data);

        return redirect()->route('admin.scholars')->with('success', 'تم تحديث العالم بنجاح');
    }

    public function deleteScholar(User $scholar)
    {
        if ($scholar->image) {
            Storage::disk('public')->delete($scholar->image);
        }

        $scholar->delete();
        return redirect()->route('admin.scholars')->with('success', 'تم حذف العالم بنجاح');
    }

    /**
     * عرض المقالات المعلقة (قيد المراجعة)
     */
    public function pendingArticles()
    {
        $articles = Article::where('status', 'pending')
            ->with('author')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.articles.pending', compact('articles'));
    }

    /**
     * الموافقة على مقال ونشره
     */
    public function approveArticle($id)
    {
        $article = Article::findOrFail($id);
        $article->status = 'published';
        $article->published_at = now();
        $article->save();

        return redirect()->back()->with('success', 'تم نشر المقال بنجاح');
    }

    /**
     * رفض مقال
     */
    public function rejectArticle($id)
    {
        $article = Article::findOrFail($id);
        $article->status = 'draft';
        $article->save();

        return redirect()->back()->with('success', 'تم رفض المقال وإعادته إلى المسودات');
    }

    /**
     * حذف مقال
     */
    public function deleteArticle($id)
    {
        $article = Article::findOrFail($id);

        // حذف الصورة المميزة إن وجدت
        if ($article->featured_image) {
            Storage::disk('public')->delete($article->featured_image);
        }

        $article->delete();

        return redirect()->back()->with('success', 'تم حذف المقال بنجاح');
    }
}
