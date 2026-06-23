<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::published()->with('category', 'author')->latest('published_at');

        if ($request->filled('category')) {
            $category = PostCategory::where('slug', $request->category)->first();
            if ($category) {
                $query->where('post_category_id', $category->id);
            }
        }

        return view('site.blog.index', [
            'posts' => $query->paginate(6)->withQueryString(),
            'categories' => PostCategory::all(),
            'activeCategory' => $request->category,
        ]);
    }

    public function show(Post $post)
    {
        abort_unless($post->status === 'published', 404);

        return view('site.blog.show', [
            'post' => $post->load('category', 'author'),
            'related' => Post::published()
                ->where('post_category_id', $post->post_category_id)
                ->where('id', '!=', $post->id)
                ->limit(3)->get(),
        ]);
    }
}
