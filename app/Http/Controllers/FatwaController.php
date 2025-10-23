<?php

namespace App\Http\Controllers;

use App\Models\Fatwa;
use App\Models\User;
use App\Services\FatwaService;
use Illuminate\Http\Request;

class FatwaController extends Controller
{
    protected $fatwaService;

    public function __construct(FatwaService $fatwaService)
    {
        $this->fatwaService = $fatwaService;
    }
    /**
     * عرض قائمة الفتاوى
     */
    public function index()
    {
        try {
            $fatwas = $this->fatwaService->getAllFatwas(12);

            $stats = $this->fatwaService->getFatwaStats();

            return view('fatwas.index', compact('fatwas', 'stats'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء جلب الفتاوى: ' . $e->getMessage());
        }
    }

    /**
     * عرض فتوى واحدة
     */
    public function show($id)
    {
        try {
            $fatwa = $this->fatwaService->getFatwaById($id);

            // زيادة عدد المشاهدات
            $this->fatwaService->incrementViews($id);

            // فتاوى ذات صلة
            $relatedFatwas = $this->fatwaService->getRelatedFatwas($id, 5);

            return view('fatwas.show', compact('fatwa', 'relatedFatwas'));
        } catch (\Exception $e) {
            return redirect()->route('fatwas.index')->with('error', 'حدث خطأ أثناء عرض الفتوى: ' . $e->getMessage());
        }
    }

    /**
     * عرض الفتاوى حسب التصنيف
     */
    public function byCategory($category)
    {
        try {
            $fatwas = $this->fatwaService->getFatwasByCategory($category, 12);

            $categoryNames = [
                'worship' => 'العبادات',
                'transactions' => 'المعاملات',
                'family' => 'الأسرة',
                'contemporary' => 'القضايا المعاصرة',
                'ethics' => 'الأخلاق والآداب',
                'beliefs' => 'العقيدة',
                'jurisprudence' => 'الفقه',
                'quran' => 'القرآن الكريم',
                'hadith' => 'الحديث الشريف',
            ];

            $categoryName = $categoryNames[$category] ?? $category;

            $stats = [
                'total' => $fatwas->total(),
            ];

            return view('fatwas.by-category', compact('fatwas', 'category', 'categoryName', 'stats'));
        } catch (\Exception $e) {
            return redirect()->route('fatwas.index')->with('error', 'حدث خطأ أثناء جلب الفتاوى: ' . $e->getMessage());
        }
    }

    /**
     * عرض فتاوى عالم محدد
     */
    public function byScholar($scholarId)
    {
        try {
            $scholar = User::findOrFail($scholarId);

            $fatwas = $this->fatwaService->getFatwasByScholar($scholarId, 12);

            $stats = [
                'total' => $fatwas->total(),
                'answered' => $fatwas->total(),
            ];

            return view('fatwas.by-scholar', compact('fatwas', 'scholar', 'stats'));
        } catch (\Exception $e) {
            return redirect()->route('fatwas.index')->with('error', 'حدث خطأ أثناء جلب فتاوى العالم: ' . $e->getMessage());
        }
    }

    /**
     * البحث في الفتاوى
     */
    public function search(Request $request)
    {
        try {
            $query = $request->input('q');

            if (empty($query)) {
                return redirect()->route('fatwas.index');
            }

            $fatwas = $this->fatwaService->searchFatwas($query, 20);

            return view('fatwas.search', compact('fatwas', 'query'));
        } catch (\Exception $e) {
            return redirect()->route('fatwas.index')->with('error', 'حدث خطأ أثناء البحث: ' . $e->getMessage());
        }
    }
}

