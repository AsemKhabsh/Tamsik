<?php

namespace App\Http\Controllers;

use App\Models\Sermon;
use App\Models\Article;
use App\Models\Lecture;
use App\Models\Fatwa;
use App\Models\User;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    /**
     * Generate and return the sitemap.xml
     */
    public function index()
    {
        // Get all published content
        $sermons = Sermon::where('is_published', true)
            ->select('id', 'slug', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->get();

        $articles = Article::where('status', 'published')
            ->select('id', 'slug', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->get();

        $lectures = Lecture::where('is_published', true)
            ->select('id', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->get();

        $fatwas = Fatwa::where('is_published', true)
            ->select('id', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->get();

        // Get active scholars, preachers, and thinkers
        $scholars = User::where('user_type', 'scholar')
            ->where('is_active', true)
            ->select('id', 'updated_at')
            ->get();

        $preachers = User::where('user_type', 'preacher')
            ->where('is_active', true)
            ->select('id', 'updated_at')
            ->get();

        $thinkers = User::where('user_type', 'thinker')
            ->where('is_active', true)
            ->select('id', 'updated_at')
            ->get();

        return response()->view('sitemap', compact(
            'sermons',
            'articles',
            'lectures',
            'fatwas',
            'scholars',
            'preachers',
            'thinkers'
        ))->header('Content-Type', 'text/xml');
    }
}

