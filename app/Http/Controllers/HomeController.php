<?php

namespace App\Http\Controllers;

use App\Services\HomeService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $homeService;

    public function __construct(HomeService $homeService)
    {
        $this->homeService = $homeService;
    }

    /**
     * عرض الصفحة الرئيسية
     */
    public function index()
    {
        // الإحصائيات
        $stats = $this->homeService->getHomeStats();

        // أحدث الخطب
        $latestSermons = $this->homeService->getRecentSermons(6);

        // الخطب الأكثر مشاهدة
        $featuredSermons = $this->homeService->getPopularSermons(5);

        // أحدث المقالات
        $latestArticles = $this->homeService->getRecentArticles(6);

        // المحاضرات القادمة
        $upcomingLectures = $this->homeService->getUpcomingLectures(5);

        // العلماء المميزين
        $featuredScholars = $this->homeService->getFeaturedScholars(4);

        return view('home', compact('stats', 'latestSermons', 'featuredSermons', 'latestArticles', 'upcomingLectures', 'featuredScholars'));
    }
}
