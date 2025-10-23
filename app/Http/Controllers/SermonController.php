<?php

namespace App\Http\Controllers;

use App\Models\Sermon;
use App\Models\User;
use App\Services\SermonService;
use App\Http\Requests\StoreSermonRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SermonController extends Controller
{
    protected $sermonService;

    public function __construct(SermonService $sermonService)
    {
        $this->sermonService = $sermonService;
    }
    /**
     * عرض قائمة الخطب
     */
    public function index(Request $request)
    {
        // البحث
        if ($request->has('search') && $request->search) {
            $sermons = $this->sermonService->searchSermons($request->search, 12);
        } else {
            $sermons = $this->sermonService->getAllSermons(12);
        }

        // التصنيفات المتاحة من Config
        $categories = config('categories.sermons');

        // الخطب المميزة
        $featuredSermons = $this->sermonService->getPopularSermons(6);

        return view('sermons.index', compact('sermons', 'categories', 'featuredSermons'));
    }

    /**
     * عرض خطبة محددة
     */
    public function show($id)
    {
        $sermon = $this->sermonService->getSermonById($id);

        // زيادة عدد المشاهدات
        $this->sermonService->incrementViews($id);

        // الخطب ذات الصلة
        $relatedSermons = $this->sermonService->getRelatedSermons($id, 4);

        // التحقق من إضافة الخطبة للمفضلات (للمستخدمين المسجلين)
        $isFavorited = false;

        // التصنيفات
        $categories = [
            'friday' => 'خطب الجمعة',
            'eid' => 'خطب العيد',
            'ramadan' => 'خطب رمضان',
            'hajj' => 'خطب الحج',
            'general' => 'خطب عامة',
            'occasions' => 'خطب المناسبات',
            'youth' => 'خطب الشباب',
            'women' => 'خطب النساء',
            'children' => 'خطب الأطفال'
        ];

        return view('sermons.show-simple', compact('sermon', 'relatedSermons', 'categories', 'isFavorited'));
    }

    /**
     * عرض نموذج إنشاء خطبة جديدة
     */
    public function create()
    {
        $this->authorize('create', Sermon::class);

        // استخدام التصنيفات من Config
        $categories = config('categories.sermons');

        return view('sermons.create', compact('categories'));
    }

    /**
     * حفظ خطبة جديدة
     */
    public function store(StoreSermonRequest $request)
    {
        // الـ Authorization والـ Validation تلقائي من StoreSermonRequest
        $sermon = new Sermon($request->except(['image', 'audio_file', 'tags']));
        $sermon->author_id = Auth::id();
        
        // معالجة العلامات
        if ($request->tags) {
            $sermon->tags = array_map('trim', explode(',', $request->tags));
        }

        // رفع الصورة
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('sermons', 'public');
            $sermon->image = basename($imagePath);
        }

        // رفع الملف الصوتي
        if ($request->hasFile('audio_file')) {
            $audioPath = $request->file('audio_file')->store('sermons/audio', 'public');
            $sermon->audio_file = basename($audioPath);
        }

        // تحديد حالة النشر حسب دور المستخدم
        if (Auth::user()->hasRole(['admin', 'scholar'])) {
            $sermon->is_published = true;
            $sermon->published_at = now();
        } else {
            $sermon->is_published = false; // يحتاج موافقة
        }

        $sermon->save();

        $message = $sermon->is_published 
            ? 'تم نشر الخطبة بنجاح!' 
            : 'تم حفظ الخطبة وهي في انتظار المراجعة.';

        return redirect()->route('sermons.show', $sermon->id)
            ->with('success', $message);
    }

    /**
     * تحميل الخطبة
     */
    public function download($id)
    {
        $sermon = $this->sermonService->getSermonById($id);

        // زيادة عدد التحميلات
        $this->sermonService->incrementDownloads($id);

        // إنشاء محتوى نصي للخطبة
        $content = $this->generateSermonText($sermon);

        $filename = 'خطبة_' . str_replace([' ', '/', '\\', ':', '*', '?', '"', '<', '>', '|'], '_', $sermon->title) . '.doc';

        // إنشاء محتوى HTML بسيط يمكن فتحه في Word
        $htmlContent = $this->generateWordDocument($sermon, $content);

        return response($htmlContent)
            ->header('Content-Type', 'application/msword; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'max-age=0');
    }

    /**
     * إنشاء محتوى نصي للخطبة
     */
    private function generateSermonText($sermon)
    {
        $content = "بسم الله الرحمن الرحيم\n\n";
        $content .= "منصة تمسيك الإسلامية\n";
        $content .= "========================\n\n";

        $content .= "عنوان الخطبة: " . $sermon->title . "\n";
        $content .= "الخطيب: " . ($sermon->speaker_name ?? 'غير محدد') . "\n";
        $content .= "التصنيف: " . ($sermon->category ?? 'عام') . "\n";
        $content .= "تاريخ النشر: " . $sermon->created_at->format('Y-m-d') . "\n";
        $content .= "========================\n\n";

        if ($sermon->introduction) {
            $content .= "المقدمة:\n";
            $content .= "--------\n";
            $content .= strip_tags($sermon->introduction) . "\n\n";
        }

        $content .= "محتوى الخطبة:\n";
        $content .= "-------------\n";
        $content .= strip_tags($sermon->content) . "\n\n";

        if ($sermon->conclusion) {
            $content .= "الخاتمة:\n";
            $content .= "--------\n";
            $content .= strip_tags($sermon->conclusion) . "\n\n";
        }

        $content .= "========================\n";
        $content .= "تم التحميل من منصة تمسيك الإسلامية\n";
        $content .= "info@tamsik.org\n";

        return $content;
    }

    /**
     * إنشاء مستند Word بصيغة HTML
     */
    private function generateWordDocument($sermon, $textContent)
    {
        $html = '<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>' . htmlspecialchars($sermon->title) . '</title>
    <style>
        body {
            font-family: "Traditional Arabic", "Arial", sans-serif;
            direction: rtl;
            text-align: right;
            line-height: 1.8;
            margin: 2cm;
        }
        h1 {
            color: #2c5530;
            text-align: center;
            font-size: 24pt;
            margin-bottom: 10pt;
        }
        h2 {
            color: #2c5530;
            font-size: 18pt;
            margin-top: 20pt;
            margin-bottom: 10pt;
            border-bottom: 2px solid #2c5530;
            padding-bottom: 5pt;
        }
        .header {
            text-align: center;
            margin-bottom: 30pt;
            border-bottom: 3px double #2c5530;
            padding-bottom: 15pt;
        }
        .meta {
            background-color: #f5f5f5;
            padding: 15pt;
            margin: 20pt 0;
            border-right: 4px solid #2c5530;
        }
        .content {
            text-align: justify;
            font-size: 14pt;
            line-height: 2;
        }
        .footer {
            margin-top: 30pt;
            padding-top: 15pt;
            border-top: 2px solid #2c5530;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>بسم الله الرحمن الرحيم</h1>
        <p style="font-size: 16pt; color: #2c5530;">منصة تمسيك الإسلامية</p>
    </div>

    <h1>' . htmlspecialchars($sermon->title) . '</h1>

    <div class="meta">
        <p><strong>الكاتب:</strong> ' . htmlspecialchars($sermon->author->name ?? 'غير محدد') . '</p>
        <p><strong>التصنيف:</strong> ' . htmlspecialchars($sermon->category ?? 'عام') . '</p>
        <p><strong>تاريخ النشر:</strong> ' . $sermon->created_at->format('Y-m-d') . '</p>
    </div>';

        if ($sermon->introduction) {
            $html .= '
    <h2>المقدمة</h2>
    <div class="content">' . nl2br(htmlspecialchars(strip_tags($sermon->introduction))) . '</div>';
        }

        $html .= '
    <h2>محتوى الخطبة</h2>
    <div class="content">' . nl2br(htmlspecialchars(strip_tags($sermon->content))) . '</div>';

        if ($sermon->conclusion) {
            $html .= '
    <h2>الخاتمة</h2>
    <div class="content">' . nl2br(htmlspecialchars(strip_tags($sermon->conclusion))) . '</div>';
        }

        if ($sermon->references && count($sermon->references) > 0) {
            $html .= '
    <h2>المراجع</h2>
    <ul>';
            foreach ($sermon->references as $reference) {
                $html .= '<li>' . htmlspecialchars($reference) . '</li>';
            }
            $html .= '</ul>';
        }

        $html .= '
    <div class="footer">
        <p>تم التحميل من منصة تمسيك الإسلامية</p>
        <p>info@tamsik.org</p>
    </div>
</body>
</html>';

        return $html;
    }

    /**
     * إضافة/إزالة من المفضلات
     */
    public function toggleFavorite($id)
    {
        $sermon = Sermon::published()->findOrFail($id);
        $user = Auth::user();

        $favorite = $user->favorites()->where('sermon_id', $sermon->id)->first();

        if ($favorite) {
            $favorite->delete();
            $message = 'تم إزالة الخطبة من المفضلات';
            $status = 'removed';
        } else {
            $user->favorites()->create(['sermon_id' => $sermon->id]);
            $message = 'تم إضافة الخطبة للمفضلات';
            $status = 'added';
        }

        if (request()->ajax()) {
            return response()->json([
                'status' => $status,
                'message' => $message
            ]);
        }

        return back()->with('success', $message);
    }

}
