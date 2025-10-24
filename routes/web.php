<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SermonController;
use App\Http\Controllers\ScholarController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\ThinkerController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SermonPreparationController;
use App\Http\Controllers\LectureManagementController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

// مسار مؤقت للتحقق من بيانات المستخدم
Route::get('/debug-user', function() {
    if (auth()->check()) {
        $user = auth()->user();
        return response()->json([
            'logged_in' => true,
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'user_type' => $user->user_type,
            'role' => $user->role,
            'is_active' => $user->is_active,
            'allowed_to_add_articles' => in_array($user->user_type, ['admin', 'scholar', 'thinker', 'data_entry']),
        ]);
    } else {
        return response()->json([
            'logged_in' => false,
            'message' => 'المستخدم غير مسجل دخول'
        ]);
    }
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// الصفحة الرئيسية
Route::get('/', [HomeController::class, 'index'])->name('home');

// صفحات المحتوى العامة
Route::get('/sermons', [SermonController::class, 'index'])->name('sermons.index');
Route::get('/sermons/create', [SermonController::class, 'create'])->name('sermons.create');
Route::get('/sermons/prepare', [SermonPreparationController::class, 'create'])->name('sermons.prepare');
Route::post('/sermons', [SermonController::class, 'store'])->name('sermons.store');
Route::get('/sermons/{id}', [SermonController::class, 'show'])->name('sermons.show');
Route::get('/sermons/{id}/download', [SermonController::class, 'download'])->name('sermons.download');

// Search routes
Route::get('/search', [App\Http\Controllers\SearchController::class, 'index'])->name('search.index');
Route::get('/search/quick', [App\Http\Controllers\SearchController::class, 'quick'])->name('search.quick');

// Auth routes
Route::get('/login', function() {
    return view('auth.login');
})->name('login');

Route::post('/login', function(Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials, $request->filled('remember'))) {
        $request->session()->regenerate();

        // توجيه المدير إلى لوحة الإدارة
        if (Auth::user()->role === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        }

        // توجيه باقي المستخدمين إلى الصفحة المقصودة أو الرئيسية
        return redirect()->intended(route('home'));
    }

    return back()->withErrors([
        'email' => 'البيانات المدخلة غير صحيحة.',
    ])->onlyInput('email');
})->middleware('throttle:5,1'); // حماية من Brute Force: 5 محاولات في الدقيقة

Route::get('/register', function() {
    return view('auth.register');
})->name('register');

Route::post('/register', function(Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'user_type' => 'required|string|in:member,preacher,scholar,thinker,data_entry',
        'terms' => 'required|accepted',
    ]);

    $userType = $request->user_type;
    $needsApproval = in_array($userType, ['preacher', 'scholar', 'thinker', 'data_entry']);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'user_type' => $userType,
        'role' => $userType === 'member' ? 'member' : 'pending',
        'is_active' => !$needsApproval,
    ]);

    if ($needsApproval) {
        // إرسال إشعار للأدمن (يمكن تطويره لاحقاً)
        // Mail::to('admin@tamsik.org')->send(new NewUserApprovalRequest($user));

        return redirect()->route('register')->with('warning',
            'تم إنشاء حسابك بنجاح! سيتم مراجعة طلبك من قبل الإدارة وستتلقى إشعاراً عبر البريد الإلكتروني عند تفعيل حسابك.');
    }

    Auth::login($user);
    return redirect('/')->with('success', 'مرحباً بك في منصة تمسيك!');
});

Route::post('/logout', function(Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Route مؤقت لتسجيل الدخول السريع كمدير (للتطوير فقط - يجب حذفه في الإنتاج)
Route::get('/quick-admin-login', function(Request $request) {
    $admin = User::where('email', 'admin@tamsik.com')->first();
    if ($admin) {
        Auth::login($admin, true); // remember = true
        $request->session()->regenerate();
        return redirect('/admin')->with('success', 'تم تسجيل الدخول كمدير');
    }
    return redirect('/login')->with('error', 'لم يتم العثور على حساب المدير');
});

// Route مؤقت لتسجيل الدخول السريع كخطيب (للتطوير فقط - يجب حذفه في الإنتاج)
Route::get('/quick-preacher-login', function(Request $request) {
    $preacher = User::where('email', 'preacher@tamsik.com')->first();
    if ($preacher) {
        Auth::login($preacher, true); // remember = true
        $request->session()->regenerate();
        return redirect('/sermons/prepare')->with('success', 'تم تسجيل الدخول كخطيب');
    }
    return redirect('/login')->with('error', 'لم يتم العثور على حساب الخطيب');
});

// مسار اختبار تحسينات UI/UX
Route::get('/test-ui', function () {
    return view('test-ui');
})->name('test.ui');

Route::get('/scholars', [ScholarController::class, 'index'])->name('scholars.index');
Route::get('/scholars/ask-question', [ScholarController::class, 'askQuestion'])->name('scholars.ask-question')->middleware('auth');
Route::post('/scholars/ask-question', [ScholarController::class, 'submitQuestion'])->name('scholars.submit-question')->middleware('auth');
Route::get('/scholars/{id}', [ScholarController::class, 'show'])->name('scholars.show');

Route::get('/lectures', [LectureController::class, 'index'])->name('lectures.index');
Route::get('/lectures/{id}', [LectureController::class, 'show'])->name('lectures.show');

Route::get('/thinkers', [ThinkerController::class, 'index'])->name('thinkers.index');
Route::get('/thinkers/{id}', [ThinkerController::class, 'show'])->name('thinkers.show');

// مسارات المقالات العامة (للزوار)
Route::get('/articles/{id}', [ArticleController::class, 'show'])->name('articles.show');

// مسارات التعليقات والإعجابات والتقييمات (تتطلب تسجيل دخول)
Route::middleware('auth')->group(function () {
    // التعليقات
    Route::post('/articles/{article}/comments', [CommentController::class, 'store'])->name('articles.comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // الإعجابات - نظام موحد لجميع الأنواع
    Route::post('/{type}/{id}/like', [LikeController::class, 'toggle'])->name('like.toggle');

    // التقييمات - للخطب والمحاضرات
    Route::post('/{type}/{id}/rating', [RatingController::class, 'store'])->name('rating.store');
    Route::delete('/{type}/{id}/rating', [RatingController::class, 'destroy'])->name('rating.destroy');
    Route::get('/{type}/{id}/rating/user', [RatingController::class, 'getUserRating'])->name('rating.user');
});

// مسارات إعداد الخطب (للخطباء والعلماء فقط)
Route::middleware('preacher')->group(function () {
    // إدارة الخطب
    Route::get('/prepare-sermon', [SermonPreparationController::class, 'create'])->name('sermon.prepare');
    Route::post('/prepare-sermon', [SermonPreparationController::class, 'store'])->name('sermon.store');
    Route::get('/my-sermons', [SermonPreparationController::class, 'mySermons'])->name('sermon.my');
    Route::get('/my-sermons/{id}/edit', [SermonPreparationController::class, 'edit'])->name('sermon.edit');
    Route::put('/my-sermons/{id}', [SermonPreparationController::class, 'update'])->name('sermon.update');

    // إدارة المحاضرات
    Route::get('/add-lecture', [LectureManagementController::class, 'create'])->name('lectures.create');
    Route::post('/add-lecture', [LectureManagementController::class, 'store'])->name('lectures.store');
    Route::get('/my-lectures', [LectureManagementController::class, 'myLectures'])->name('lectures.my');
    Route::get('/my-lectures/{id}/edit', [LectureManagementController::class, 'edit'])->name('lectures.edit');
    Route::put('/my-lectures/{id}', [LectureManagementController::class, 'update'])->name('lectures.update');
    Route::delete('/my-lectures/{id}', [LectureManagementController::class, 'destroy'])->name('lectures.destroy');

    // إدارة المقالات (للمفكرين والعلماء)
    Route::get('/add-article', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/add-article', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/my-articles', [ArticleController::class, 'myArticles'])->name('articles.my');
    Route::get('/my-articles/{id}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/my-articles/{id}', [ArticleController::class, 'update'])->name('articles.update');
    Route::delete('/my-articles/{id}', [ArticleController::class, 'destroy'])->name('articles.destroy');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Users Management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');

    // Sermons Management
    Route::get('/sermons', [AdminController::class, 'sermons'])->name('sermons');
    Route::get('/sermons/{sermon}/edit', [AdminController::class, 'editSermon'])->name('sermons.edit');
    Route::put('/sermons/{sermon}', [AdminController::class, 'updateSermon'])->name('sermons.update');
    Route::delete('/sermons/{sermon}', [AdminController::class, 'deleteSermon'])->name('sermons.delete');

    // Lectures Management
    Route::get('/lectures', [AdminController::class, 'lectures'])->name('lectures');
    Route::get('/lectures/{lecture}/edit', [AdminController::class, 'editLecture'])->name('lectures.edit');
    Route::put('/lectures/{lecture}', [AdminController::class, 'updateLecture'])->name('lectures.update');
    Route::delete('/lectures/{lecture}', [AdminController::class, 'deleteLecture'])->name('lectures.delete');

    // Scholars Management
    Route::get('/scholars', [AdminController::class, 'scholars'])->name('scholars');
    Route::get('/scholars/create', [AdminController::class, 'createScholar'])->name('scholars.create');
    Route::post('/scholars', [AdminController::class, 'storeScholar'])->name('scholars.store');
    Route::get('/scholars/{scholar}/edit', [AdminController::class, 'editScholar'])->name('scholars.edit');
    Route::put('/scholars/{scholar}', [AdminController::class, 'updateScholar'])->name('scholars.update');
    Route::delete('/scholars/{scholar}', [AdminController::class, 'deleteScholar'])->name('scholars.delete');

    // Articles Management
    Route::get('/articles/pending', [AdminController::class, 'pendingArticles'])->name('articles.pending');
    Route::post('/articles/{article}/approve', [AdminController::class, 'approveArticle'])->name('articles.approve');
    Route::post('/articles/{article}/reject', [AdminController::class, 'rejectArticle'])->name('articles.reject');
    Route::delete('/articles/{article}', [AdminController::class, 'deleteArticle'])->name('articles.delete');
});

// صفحات ثابتة
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'contactSubmit'])->name('contact.submit');

// Routes إضافية مفقودة
Route::middleware('auth')->group(function () {
    Route::get('/profile', function() {
        return view('profile.index');
    })->name('profile');

    Route::get('/favorites', function() {
        return view('favorites.index');
    })->name('favorites');
});

// مسارات الفتاوى العامة (للزوار)
Route::get('/fatwas', [\App\Http\Controllers\FatwaController::class, 'index'])->name('fatwas.index');
Route::get('/fatwas/category/{category}', [\App\Http\Controllers\FatwaController::class, 'byCategory'])->name('fatwas.category');
Route::get('/fatwas/scholar/{scholarId}', [\App\Http\Controllers\FatwaController::class, 'byScholar'])->name('fatwas.scholar');
Route::get('/fatwas/search', [\App\Http\Controllers\FatwaController::class, 'search'])->name('fatwas.search')->middleware('throttle:60,1');
Route::get('/fatwas/{id}', [\App\Http\Controllers\FatwaController::class, 'show'])->name('fatwas.show');

// مسارات طرح الأسئلة (تتطلب تسجيل دخول)
Route::middleware('auth')->group(function () {
    Route::get('/ask-question', [\App\Http\Controllers\QuestionController::class, 'create'])->name('questions.ask');
    Route::post('/ask-question', [\App\Http\Controllers\QuestionController::class, 'store'])->name('questions.store');
    Route::get('/my-questions', [\App\Http\Controllers\QuestionController::class, 'myQuestions'])->name('questions.my');
});

Route::get('/articles', function() {
    return view('coming-soon', ['title' => 'المقالات']);
})->name('articles.index');

// مسار تجريبي لصفحة إعداد الخطبة (للاختبار فقط - يجب حذفه في الإنتاج)
Route::get('/test-sermon-prepare', function() {
    $categories = [
        'عقيدة' => 'العقيدة',
        'عبادات' => 'العبادات',
        'معاملات' => 'المعاملات',
        'أخلاق' => 'الأخلاق والآداب',
        'سيرة' => 'السيرة النبوية',
        'تربية' => 'التربية والدعوة',
        'أسرة' => 'الأسرة والمجتمع',
        'معاصرة' => 'قضايا معاصرة',
    ];
    return view('sermons.prepare', compact('categories'));
})->name('test.sermon.prepare');
