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
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

// مسار مؤقت للتحقق من بيانات المستخدم (للتطوير فقط)
if (app()->environment('local')) {
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
}

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

// Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// صفحات المحتوى العامة
Route::get('/sermons', [SermonController::class, 'index'])->name('sermons.index');
Route::get('/sermons/create', [SermonController::class, 'create'])->name('sermons.create')->middleware('auth');
Route::post('/sermons', [SermonController::class, 'store'])->name('sermons.store')->middleware('auth');
Route::get('/sermons/{id}', [SermonController::class, 'show'])->name('sermons.show');
Route::get('/sermons/{id}/download', [SermonController::class, 'download'])->name('sermons.download');

// Search routes (with rate limiting to prevent abuse)
Route::get('/search', [App\Http\Controllers\SearchController::class, 'index'])
    ->name('search.index')
    ->middleware('throttle:60,1'); // 60 requests per minute
Route::get('/search/quick', [App\Http\Controllers\SearchController::class, 'quick'])
    ->name('search.quick')
    ->middleware('throttle:60,1'); // 60 requests per minute

// Auth routes - استخدام AuthController
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1'); // حماية من Brute Force: 5 محاولات في الدقيقة

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Routes مؤقتة لتسجيل الدخول السريع (للتطوير المحلي فقط)
if (app()->environment('local')) {
    Route::get('/quick-admin-login', [AuthController::class, 'quickAdminLogin']);

    // Quick preacher login - يمكن نقله لاحقاً إلى AuthController
    Route::get('/quick-preacher-login', function(Request $request) {
        $preacher = User::where('email', 'preacher@tamsik.com')->first();
        if ($preacher) {
            Auth::login($preacher, true);
            $request->session()->regenerate();
            return redirect('/sermons/prepare')->with('success', 'تم تسجيل الدخول كخطيب');
        }
        return redirect('/login')->with('error', 'لم يتم العثور على حساب الخطيب');
    });
}

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

// مسارات إعداد الخطب (للخطباء والعلماء والأدمن)
Route::middleware('preacher')->group(function () {
    // إدارة الخطب
    Route::get('/sermons/prepare', [SermonPreparationController::class, 'create'])->name('sermons.prepare');
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

    // Fatwas Management
    Route::get('/fatwas', [AdminController::class, 'fatwas'])->name('fatwas');
    Route::get('/fatwas/create', [AdminController::class, 'createFatwa'])->name('fatwas.create');
    Route::post('/fatwas', [AdminController::class, 'storeFatwa'])->name('fatwas.store');
    Route::get('/fatwas/{fatwa}/edit', [AdminController::class, 'editFatwa'])->name('fatwas.edit');
    Route::put('/fatwas/{fatwa}', [AdminController::class, 'updateFatwa'])->name('fatwas.update');
    Route::delete('/fatwas/{fatwa}', [AdminController::class, 'deleteFatwa'])->name('fatwas.delete');
});

// صفحات ثابتة
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'contactSubmit'])->name('contact.submit');

// مسارات الملف الشخصي والمفضلات
Route::middleware('auth')->group(function () {
    // الملف الشخصي
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/change-password', [\App\Http\Controllers\ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::delete('/profile/avatar', [\App\Http\Controllers\ProfileController::class, 'deleteAvatar'])->name('profile.delete-avatar');

    // الإشعارات
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread', [\App\Http\Controllers\NotificationController::class, 'unread'])->name('notifications.unread');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');

    // المفضلات
    Route::get('/favorites', [\App\Http\Controllers\FavoriteController::class, 'index'])->name('favorites');
    Route::post('/favorites/toggle', [\App\Http\Controllers\FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::post('/favorites/store', [\App\Http\Controllers\FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/destroy', [\App\Http\Controllers\FavoriteController::class, 'destroy'])->name('favorites.destroy');
    Route::delete('/favorites/clear', [\App\Http\Controllers\FavoriteController::class, 'clear'])->name('favorites.clear');
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

// مسارات لوحة تحكم العالم (تتطلب تسجيل دخول وصلاحية عالم)
Route::prefix('scholar')->name('scholar.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\ScholarDashboardController::class, 'dashboard'])->name('dashboard');

    Route::prefix('questions')->name('questions.')->group(function () {
        Route::get('/', [\App\Http\Controllers\ScholarDashboardController::class, 'questions'])->name('index');
        Route::get('/{id}', [\App\Http\Controllers\ScholarDashboardController::class, 'showQuestion'])->name('show');
        Route::post('/{id}/answer', [\App\Http\Controllers\ScholarDashboardController::class, 'answerQuestion'])->name('answer');
        Route::put('/{id}/update', [\App\Http\Controllers\ScholarDashboardController::class, 'updateAnswer'])->name('update');
        Route::put('/{id}/publish', [\App\Http\Controllers\ScholarDashboardController::class, 'publishAnswer'])->name('publish');
        Route::put('/{id}/unpublish', [\App\Http\Controllers\ScholarDashboardController::class, 'unpublishAnswer'])->name('unpublish');
    });
});

Route::get('/articles', function() {
    return view('coming-soon', ['title' => 'المقالات']);
})->name('articles.index');

// مسار تجريبي لصفحة إعداد الخطبة (للتطوير المحلي فقط)
if (app()->environment('local')) {
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
}
