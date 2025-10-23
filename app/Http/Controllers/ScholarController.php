<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sermon;
use App\Models\Fatwa;
use App\Services\ScholarService;
use App\Services\FatwaService;
use Illuminate\Http\Request;

class ScholarController extends Controller
{
    protected $scholarService;
    protected $fatwaService;

    public function __construct(ScholarService $scholarService, FatwaService $fatwaService)
    {
        $this->scholarService = $scholarService;
        $this->fatwaService = $fatwaService;
    }
    /**
     * عرض قائمة العلماء
     */
    public function index()
    {
        $scholars = $this->scholarService->getAllScholars(12);

        // الحصول على الفتاوى الحديثة
        $recentFatwas = $this->fatwaService->getRecentFatwas(5);

        // إحصائيات الفتاوى
        $fatwaStats = $this->fatwaService->getFatwaStats();

        // إحصائيات الفتاوى حسب التصنيف
        $worshipFatwasCount = 0;
        $transactionsFatwasCount = 0;
        $familyFatwasCount = 0;
        $contemporaryFatwasCount = 0;
        $ethicsFatwasCount = 0;

        // إحصائيات عامة
        $totalFatwas = $fatwaStats['total'] ?? 0;
        $pendingQuestions = $fatwaStats['unanswered'] ?? 0;
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
        $scholar = $this->scholarService->getScholarById($id);

        // خطب العالم
        $sermons = $this->scholarService->getScholarSermons($id, 10);

        // فتاوى العالم
        $fatwas = $this->scholarService->getScholarFatwas($id, 10);

        // إحصائيات العالم
        $stats = $this->scholarService->getScholarStats($id);

        return view('scholars.show', compact('scholar', 'sermons', 'fatwas', 'stats'));
    }

    /**
     * عرض صفحة طرح سؤال
     */
    public function askQuestion()
    {
        $scholars = $this->scholarService->getAllScholars(100);

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
