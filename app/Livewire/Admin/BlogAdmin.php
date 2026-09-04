<?php

namespace App\Livewire\Admin;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class BlogAdmin extends Component
{
    use WithPagination;

    public $search = '';
    public $activeTab = 'posts'; // posts or categories

    // Post fields
    public $postId = null;
    public $title = '';
    public $slug = '';
    public $excerpt = '';
    public $body = '';
    public $categoryId = '';
    public $isPublished = false;
    public $publishedAt = '';
    public $featuredImage = '';
    public $metaDescription = '';
    public $showPostModal = false;

    // Category fields
    public $categoryIdField = null;
    public $categoryName = '';
    public $categorySlug = '';
    public $categoryDescription = '';
    public $showCategoryModal = false;

    protected $rules = [
        'title' => 'required|string|max:200',
        'body' => 'required|string',
        'categoryId' => 'required|exists:blog_categories,id',
        'excerpt' => 'nullable|string|max:500',
        'featuredImage' => 'nullable|url',
        'metaDescription' => 'nullable|string|max:160',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openPostModal($id = null)
    {
        $this->resetValidation();
        $this->postId = $id;

        if ($id) {
            $post = BlogPost::findOrFail($id);
            $this->title = $post->title;
            $this->slug = $post->slug;
            $this->excerpt = $post->excerpt;
            $this->body = $post->body;
            $this->categoryId = $post->category_id;
            $this->isPublished = $post->is_published;
            $this->publishedAt = $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '';
            $this->featuredImage = $post->featured_image;
            $this->metaDescription = $post->meta_description;
        } else {
            $this->title = '';
            $this->slug = '';
            $this->excerpt = '';
            $this->body = '';
            $this->categoryId = BlogCategory::first()?->id ?? '';
            $this->isPublished = false;
            $this->publishedAt = now()->format('Y-m-d\TH:i');
            $this->featuredImage = '';
            $this->metaDescription = '';
        }

        $this->showPostModal = true;
    }

    public function savePost()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'slug' => $this->slug ?: Str::slug($this->title),
            'excerpt' => $this->excerpt,
            'body' => $this->body,
            'category_id' => $this->categoryId,
            'is_published' => $this->isPublished,
            'published_at' => $this->isPublished ? ($this->publishedAt ? new \DateTime($this->publishedAt) : now()) : null,
            'featured_image' => $this->featuredImage,
            'meta_description' => $this->metaDescription,
            'author_id' => auth()->id(),
        ];

        if ($this->postId) {
            $post = BlogPost::findOrFail($this->postId);
            $post->update($data);
            session()->flash('message', 'Post updated successfully!');
        } else {
            BlogPost::create($data);
            session()->flash('message', 'Post created successfully!');
        }

        $this->showPostModal = false;
    }

    public function deletePost($id)
    {
        BlogPost::findOrFail($id)->delete();
        session()->flash('message', 'Post deleted successfully!');
    }

    public function openCategoryModal($id = null)
    {
        $this->resetValidation();
        $this->categoryIdField = $id;

        if ($id) {
            $cat = BlogCategory::findOrFail($id);
            $this->categoryName = $cat->name;
            $this->categorySlug = $cat->slug;
            $this->categoryDescription = $cat->description;
        } else {
            $this->categoryName = '';
            $this->categorySlug = '';
            $this->categoryDescription = '';
        }

        $this->showCategoryModal = true;
    }

    public function saveCategory()
    {
        $this->validate([
            'categoryName' => 'required|string|max:100',
            'categoryDescription' => 'nullable|string|max:200',
        ]);

        $data = [
            'name' => $this->categoryName,
            'slug' => $this->categorySlug ?: Str::slug($this->categoryName),
            'description' => $this->categoryDescription,
        ];

        if ($this->categoryIdField) {
            BlogCategory::findOrFail($this->categoryIdField)->update($data);
            session()->flash('message', 'Category updated successfully!');
        } else {
            BlogCategory::create($data);
            session()->flash('message', 'Category created successfully!');
        }

        $this->showCategoryModal = false;
    }

    public function deleteCategory($id)
    {
        BlogCategory::findOrFail($id)->delete();
        session()->flash('message', 'Category deleted successfully!');
    }

    public function render()
    {
        $posts = BlogPost::with(['category', 'author'])
            ->where('title', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        $categories = BlogCategory::withCount('posts')
            ->where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->get();

        return view('livewire.admin.blog-admin', compact('posts', 'categories'))
            ->layout('layouts.app');
    }
}
