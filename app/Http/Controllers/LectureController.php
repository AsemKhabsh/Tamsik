<?php

namespace App\Http\Controllers;

use App\Models\Lecture;
use App\Models\User;
use Illuminate\Http\Request;

class LectureController extends Controller
{
    /**
     * عرض قائمة المحاضرات
     */
    public function index()
    {
        $lectures = Lecture::with('speaker')->get();

        $upcomingLectures = Lecture::with('speaker')
            ->where('scheduled_at', '>', now())
            ->orderBy('scheduled_at', 'asc')
            ->limit(6)
            ->get();

        $pastLectures = Lecture::with('speaker')
            ->where('scheduled_at', '<=', now())
            ->orderBy('scheduled_at', 'desc')
            ->limit(12)
            ->get();

        return view('lectures.index', compact('lectures', 'upcomingLectures', 'pastLectures'));
    }

    /**
     * عرض محاضرة محددة
     */
    public function show($id)
    {
        $lecture = Lecture::findOrFail($id);

        return view('lectures.show', compact('lecture'));
    }
}
