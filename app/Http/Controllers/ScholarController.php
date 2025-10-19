<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sermon;
use Illuminate\Http\Request;

class ScholarController extends Controller
{
    /**
     * عرض قائمة العلماء
     */
    public function index()
    {
        $scholars = User::where('user_type', 'scholar')
            ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->paginate(12);

        // الحصول على الفتاوى الحديثة (مؤقتاً فارغة حتى ننشئ model للفتاوى)
        $recentFatwas = collect([]);

        // إحصائيات الفتاوى حسب التصنيف
        $worshipFatwasCount = 0;
        $transactionsFatwasCount = 0;
        $familyFatwasCount = 0;
        $contemporaryFatwasCount = 0;
        $ethicsFatwasCount = 0;

        // إحصائيات عامة
        $totalFatwas = 0;
        $pendingQuestions = 0;
        $activeUsers = User::count();

        return view('scholars.index', compact(
            'scholars',
            'recentFatwas',
            'worshipFatwasCount',
            'transactionsFatwasCount',
            'familyFatwasCount',
            'contemporaryFatwasCount',
            'ethicsFatwasCount',
            'totalFatwas',
            'pendingQuestions',
            'activeUsers'
        ));
    }

    /**
     * عرض ملف عالم محدد
     */
    public function show($id)
    {
        $scholar = User::where('is_active', true)
            ->findOrFail($id);

        // خطب العالم
        $sermons = Sermon::where('author_id', $scholar->id)
            ->where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // إحصائيات العالم
        $stats = [
            'sermons_count' => Sermon::where('author_id', $scholar->id)
                ->where('is_published', true)
                ->count(),
            'total_views' => Sermon::where('author_id', $scholar->id)
                ->where('is_published', true)
                ->sum('views_count'),
            'total_downloads' => Sermon::where('author_id', $scholar->id)
                ->where('is_published', true)
                ->sum('downloads_count'),
        ];

        return view('scholars.show', compact('scholar', 'sermons', 'stats'));
    }

    /**
     * عرض صفحة طرح سؤال
     */
    public function askQuestion()
    {
        $scholars = User::where('user_type', 'scholar')
            ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();

        return view('scholars.ask-question', compact('scholars'));
    }

    /**
     * إرسال سؤال للعلماء
     */
    public function submitQuestion(Request $request)
    {
        $request->validate([
            'scholar_id' => 'nullable|exists:users,id',
            'subject' => 'required|string|max:255',
            'question' => 'required|string|max:2000',
            'category' => 'required|string|in:worship,transactions,family,contemporary,ethics',
            'is_public' => 'boolean'
        ]);

        // هنا يمكن إضافة منطق حفظ السؤال في قاعدة البيانات
        // Question::create([
        //     'user_id' => auth()->id(),
        //     'scholar_id' => $request->scholar_id,
        //     'subject' => $request->subject,
        //     'question' => $request->question,
        //     'category' => $request->category,
        //     'is_public' => $request->boolean('is_public'),
        //     'status' => 'pending'
        // ]);

        return back()->with('success', 'تم إرسال سؤالك بنجاح. سيتم الرد عليه في أقرب وقت ممكن.');
    }
}
