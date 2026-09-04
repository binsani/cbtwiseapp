<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $categorySlug = $request->query('category');
        $query = BlogPost::published()->with(['category', 'author']);

        if ($categorySlug) {
            $category = BlogCategory::where('slug', $categorySlug)->firstOrFail();
            $query->where('category_id', $category->id);
        }

        $posts = $query->latest('published_at')->paginate(9);
        $categories = BlogCategory::withCount(['posts' => function($q) {
            $q->published();
        }])->get();

        return view('pages.blog.index', compact('posts', 'categories', 'categorySlug'));
    }

    public function show(string $slug)
    {
        $post = BlogPost::published()->where('slug', $slug)->with(['category', 'author'])->firstOrFail();
        
        $relatedPosts = BlogPost::published()
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('pages.blog.show', compact('post', 'relatedPosts'));
    }
}
