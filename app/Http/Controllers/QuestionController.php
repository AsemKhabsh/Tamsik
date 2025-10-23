<?php

namespace App\Http\Controllers;

use App\Models\Fatwa;
use App\Models\User;
use App\Services\FatwaService;
use App\Services\ScholarService;
use App\Http\Requests\StoreFatwaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    protected $fatwaService;
    protected $scholarService;

    public function __construct(FatwaService $fatwaService, ScholarService $scholarService)
    {
        $this->fatwaService = $fatwaService;
        $this->scholarService = $scholarService;
    }

    /**
     * عرض صفحة طرح سؤال
     */
    public function create()
    {
        $scholars = $this->scholarService->getAllScholars(100);

        return view('fatwas.ask', compact('scholars'));
    }

    /**
     * حفظ السؤال
     */
    public function store(StoreFatwaRequest $request)
    {
        try {
            $this->fatwaService->createQuestion([
                'title' => $request->title,
                'question' => $request->question,
                'category' => $request->category,
                'scholar_id' => $request->scholar_id,
                'questioner_id' => Auth::id(),
            ]);

            return redirect()->route('questions.my')->with('success', 'تم إرسال سؤالك بنجاح. سيتم الرد عليه قريباً.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إرسال السؤال: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * عرض أسئلة المستخدم
     */
    public function myQuestions()
    {
        $questions = $this->fatwaService->getUserQuestions(Auth::id(), 10);

        return view('fatwas.my-questions', compact('questions'));
    }
}

