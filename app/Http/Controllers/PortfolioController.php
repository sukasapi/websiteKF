<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function index(Request $request)
    {
        $categories = ProjectCategory::all();

        $query = Project::with('category')->orderBy('order');

        if ($request->filled('category')) {
            $category = ProjectCategory::where('slug', $request->category)->first();
            if ($category) {
                $query->where('project_category_id', $category->id);
            }
        }

        return view('site.portfolio.index', [
            'projects' => $query->paginate(9)->withQueryString(),
            'categories' => $categories,
            'activeCategory' => $request->category,
        ]);
    }

    public function show(Project $project)
    {
        $project->load('images', 'category');

        return view('site.portfolio.show', [
            'project' => $project,
            'related' => Project::where('project_category_id', $project->project_category_id)
                ->where('id', '!=', $project->id)
                ->limit(3)->get(),
        ]);
    }
}
