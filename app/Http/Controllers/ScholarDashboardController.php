<?php

namespace App\Http\Controllers;

use App\Models\Fatwa;
use App\Services\FatwaService;
use App\Http\Requests\AnswerFatwaRequest;
use App\Notifications\FatwaAnsweredNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScholarDashboardController extends Controller
{
    protected $fatwaService;

    public function __construct(FatwaService $fatwaService)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->hasRole('scholar') && Auth::user()->user_type !== 'scholar') {
                abort(403, 'غير مصرح لك بالوصول إلى هذه الصفحة');
            }
            return $next($request);
        });
        
        $this->fatwaService = $fatwaService;
    }

    /**
     * عرض لوحة تحكم العالم
     */
    public function dashboard()
    {
        $scholarId = Auth::id();
        
        // الحصول على الإحصائيات
        $stats = $this->fatwaService->getScholarQuestionsStats($scholarId);
        
        // الحصول على الأسئلة الحديثة قيد الانتظار
        $pendingQuestions = $this->fatwaService->getScholarQuestions($scholarId, 'pending', 5);
        
        // الحصول على آخر الإجابات المنشورة
        $recentAnswers = $this->fatwaService->getScholarQuestions($scholarId, 'answered', 5);

        return view('scholar.dashboard', compact('stats', 'pendingQuestions', 'recentAnswers'));
    }

    /**
     * عرض قائمة الأسئلة
     */
    public function questions(Request $request)
    {
        $scholarId = Auth::id();
        $status = $request->get('status', 'all');
        
        $questions = $this->fatwaService->getScholarQuestions($scholarId, $status, 20);
        $stats = $this->fatwaService->getScholarQuestionsStats($scholarId);

        return view('scholar.questions.index', compact('questions', 'stats', 'status'));
    }

    /**
     * عرض سؤال محدد للإجابة عليه
     */
    public function showQuestion($id)
    {
        $scholarId = Auth::id();
        
        try {
            $question = $this->fatwaService->getScholarQuestion($scholarId, $id);
            return view('scholar.questions.show', compact('question'));
        } catch (\Exception $e) {
            return redirect()->route('scholar.questions.index')
                ->with('error', 'السؤال غير موجود أو غير مخصص لك');
        }
    }

    /**
     * حفظ الإجابة على سؤال
     */
    public function answerQuestion(Request $request, $id)
    {
        $scholarId = Auth::id();
        
        $request->validate([
            'answer' => 'required|string|min:50',
            'references' => 'nullable|string',
            'tags' => 'nullable|string',
            'is_published' => 'boolean',
        ], [
            'answer.required' => 'نص الإجابة مطلوب',
            'answer.min' => 'الإجابة يجب أن تكون على الأقل 50 حرفاً',
        ]);

        try {
            $question = $this->fatwaService->getScholarQuestion($scholarId, $id);
            
            $tags = $request->tags ? array_map('trim', explode(',', $request->tags)) : [];
            $references = $request->references ? array_map('trim', explode("\n", $request->references)) : [];
            
            $isPublished = $request->has('is_published') && $request->is_published == '1';
            
            $question->update([
                'answer' => $request->answer,
                'tags' => $tags,
                'references' => $references,
                'is_published' => $isPublished,
                'published_at' => $isPublished ? now() : null,
            ]);

            $message = $isPublished 
                ? 'تم نشر الإجابة بنجاح' 
                : 'تم حفظ الإجابة كمسودة';

            return redirect()->route('scholar.questions.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حفظ الإجابة: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * تحديث إجابة موجودة
     */
    public function updateAnswer(Request $request, $id)
    {
        $scholarId = Auth::id();
        
        $request->validate([
            'answer' => 'required|string|min:50',
            'references' => 'nullable|string',
            'tags' => 'nullable|string',
            'is_published' => 'boolean',
        ], [
            'answer.required' => 'نص الإجابة مطلوب',
            'answer.min' => 'الإجابة يجب أن تكون على الأقل 50 حرفاً',
        ]);

        try {
            $question = $this->fatwaService->getScholarQuestion($scholarId, $id);
            
            $tags = $request->tags ? array_map('trim', explode(',', $request->tags)) : [];
            $references = $request->references ? array_map('trim', explode("\n", $request->references)) : [];
            
            $isPublished = $request->has('is_published') && $request->is_published == '1';
            
            $wasPublished = $question->is_published;

            $question->update([
                'answer' => $request->answer,
                'tags' => $tags,
                'references' => $references,
                'is_published' => $isPublished,
                'published_at' => $isPublished && !$question->published_at ? now() : $question->published_at,
            ]);

            // إرسال إشعار للسائل إذا تم النشر لأول مرة
            if ($isPublished && !$wasPublished && $question->questioner) {
                $question->questioner->notify(new FatwaAnsweredNotification($question));
            }

            $message = $isPublished
                ? 'تم تحديث ونشر الإجابة بنجاح'
                : 'تم تحديث الإجابة كمسودة';

            return redirect()->route('scholar.questions.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تحديث الإجابة: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * نشر إجابة محفوظة كمسودة
     */
    public function publishAnswer($id)
    {
        $scholarId = Auth::id();
        
        try {
            $question = $this->fatwaService->getScholarQuestion($scholarId, $id);
            
            if (empty($question->answer)) {
                return redirect()->back()
                    ->with('error', 'لا يمكن نشر سؤال بدون إجابة');
            }
            
            $question->update([
                'is_published' => true,
                'published_at' => now(),
            ]);

            // إرسال إشعار للسائل
            if ($question->questioner) {
                $question->questioner->notify(new FatwaAnsweredNotification($question));
            }

            return redirect()->route('scholar.questions.index')
                ->with('success', 'تم نشر الإجابة بنجاح');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء نشر الإجابة: ' . $e->getMessage());
        }
    }

    /**
     * إلغاء نشر إجابة
     */
    public function unpublishAnswer($id)
    {
        $scholarId = Auth::id();
        
        try {
            $question = $this->fatwaService->getScholarQuestion($scholarId, $id);
            
            $question->update([
                'is_published' => false,
            ]);

            return redirect()->route('scholar.questions.index')
                ->with('success', 'تم إلغاء نشر الإجابة بنجاح');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إلغاء نشر الإجابة: ' . $e->getMessage());
        }
    }
}

