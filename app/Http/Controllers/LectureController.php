<?php

namespace App\Http\Controllers;

use App\Models\Lecture;
use App\Models\User;
use App\Services\LectureService;
use Illuminate\Http\Request;

class LectureController extends Controller
{
    protected $lectureService;

    public function __construct(LectureService $lectureService)
    {
        $this->lectureService = $lectureService;
    }

    /**
     * عرض قائمة المحاضرات
     */
    public function index()
    {
        $lectures = $this->lectureService->getAllLectures(12);

        $upcomingLectures = $this->lectureService->getUpcomingLectures(6);

        $pastLectures = $this->lectureService->getRecentLectures(12);

        return view('lectures.index', compact('lectures', 'upcomingLectures', 'pastLectures'));
    }

    /**
     * عرض محاضرة محددة
     */
    public function show($id)
    {
        $lecture = $this->lectureService->getLectureById($id);

        // زيادة عدد المشاهدات
        $this->lectureService->incrementViews($id);

        return view('lectures.show', compact('lecture'));
    }
}
