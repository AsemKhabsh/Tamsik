<?php

namespace App\Http\Controllers;

use App\Models\Sermon;
use App\Models\User;
use App\Models\Lecture;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * عرض صفحة البحث
     */
    public function index(Request $request)
    {
        $query = $request->get('q');
        $type = $request->get('type', 'all'); // all, sermons, scholars, lectures
        
        $results = [
            'sermons' => collect(),
            'scholars' => collect(),
            'lectures' => collect(),
        ];
        
        $totalResults = 0;
        
        if ($query && strlen($query) >= 2) {
            // البحث في الخطب
            if ($type === 'all' || $type === 'sermons') {
                $results['sermons'] = Sermon::where('is_published', true)
                    ->where(function($q) use ($query) {
                        $q->where('title', 'LIKE', "%{$query}%")
                          ->orWhere('content', 'LIKE', "%{$query}%")
                          ->orWhere('introduction', 'LIKE', "%{$query}%")
                          ->orWhere('conclusion', 'LIKE', "%{$query}%");
                    })
                    ->orderBy('views_count', 'desc')
                    ->limit(20)
                    ->get();
            }
            
            // البحث في العلماء
            if ($type === 'all' || $type === 'scholars') {
                $results['scholars'] = User::where('is_active', true)
                    ->where(function($q) use ($query) {
                        $q->where('name', 'LIKE', "%{$query}%")
                          ->orWhere('bio', 'LIKE', "%{$query}%");
                    })
                    ->orderBy('name', 'asc')
                    ->limit(20)
                    ->get();
            }
            
            // البحث في المحاضرات
            if ($type === 'all' || $type === 'lectures') {
                $results['lectures'] = Lecture::where(function($q) use ($query) {
                        $q->where('title', 'LIKE', "%{$query}%")
                          ->orWhere('description', 'LIKE', "%{$query}%");
                    })
                    ->orderBy('scheduled_at', 'desc')
                    ->limit(20)
                    ->get();
            }
            
            $totalResults = $results['sermons']->count() + 
                           $results['scholars']->count() + 
                           $results['lectures']->count();
        }
        
        return view('search.index', compact('query', 'type', 'results', 'totalResults'));
    }
    
    /**
     * البحث السريع (AJAX)
     */
    public function quick(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query || strlen($query) < 2) {
            return response()->json([
                'sermons' => [],
                'scholars' => [],
                'total' => 0
            ]);
        }
        
        // البحث السريع في الخطب (أول 5 نتائج)
        $sermons = Sermon::where('is_published', true)
            ->where('title', 'LIKE', "%{$query}%")
            ->select('id', 'title', 'views_count')
            ->orderBy('views_count', 'desc')
            ->limit(5)
            ->get();
        
        // البحث السريع في العلماء (أول 5 نتائج)
        $scholars = User::where('is_active', true)
            ->where('name', 'LIKE', "%{$query}%")
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->limit(5)
            ->get();
        
        return response()->json([
            'sermons' => $sermons->map(function($sermon) {
                return [
                    'id' => $sermon->id,
                    'title' => $sermon->title,
                    'url' => route('sermons.show', $sermon->id),
                    'views' => number_format($sermon->views_count)
                ];
            }),
            'scholars' => $scholars->map(function($scholar) {
                return [
                    'id' => $scholar->id,
                    'name' => $scholar->name,
                    'url' => route('scholars.show', $scholar->id)
                ];
            }),
            'total' => $sermons->count() + $scholars->count()
        ]);
    }
}
