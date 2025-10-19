<?php

namespace App\Http\Controllers;

use App\Models\Sermon;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * عرض الصفحة الرئيسية
     */
    public function index()
    {
        // إحصائيات
        $stats = [
            'sermons' => Sermon::count(),
            'scholars' => User::where('user_type', 'scholar')->count(),
            'lectures' => 0,
            'users' => User::count()
        ];

        // بيانات فارغة للآن
        $latestSermons = collect();
        $featuredSermons = collect();
        $latestArticles = collect();
        $upcomingLectures = collect();
        $featuredScholars = collect();

        return view('home', compact('stats', 'latestSermons', 'featuredSermons', 'latestArticles', 'upcomingLectures', 'featuredScholars'));
    }

}
