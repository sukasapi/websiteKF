<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\TeamMember;

class SiteController extends Controller
{
    public function home()
    {
        return view('site.home', [
            'services' => Service::where('is_active', true)->orderBy('order')->get(),
            'featuredProjects' => Project::where('is_featured', true)->orderBy('order')->limit(6)->get(),
            'latestPosts' => Post::published()->latest('published_at')->limit(3)->get(),
        ]);
    }

    public function about()
    {
        return view('site.about', [
            'page' => Page::where('key', 'about')->first(),
            'team' => TeamMember::orderBy('order')->get(),
        ]);
    }

    public function services()
    {
        return view('site.services', [
            'services' => Service::where('is_active', true)->orderBy('order')->get(),
        ]);
    }
}
