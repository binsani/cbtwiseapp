@extends('layouts.public')

@section('title', 'Exam Guides & Study Tips — CBTWise Blog')
@section('meta_description', 'Read the latest exam prep guides, time-management tips, and syllabus breakdowns for JAMB UTME, WAEC, and NECO.')

@section('content')
<section class="py-20 bg-gradient-to-b from-emerald-50/20 via-white to-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-6 text-sm text-slate-500 font-medium" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ url('/') }}" class="hover:text-emerald-600 transition-colors">Home</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-slate-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ml-1 text-slate-400 md:ml-2">Blog</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="max-w-3xl mb-12">
            <h1 class="text-4xl font-black text-slate-950 font-heading tracking-tight leading-none">
                Exam Preparation <span class="bg-clip-text text-transparent bg-gradient-to-r from-emerald-600 to-teal-500">Guides</span>
            </h1>
            <p class="mt-4 text-slate-600">
                Actionable advice, exam strategies, and curriculum breakdowns curated by expert educators to help you succeed.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar / Categories -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white p-6 rounded-3xl border border-slate-100/80 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-900 font-heading mb-4 border-b border-slate-50 pb-2">Categories</h3>
                    <ul class="space-y-2 text-sm font-medium">
                        <li>
                            <a href="{{ route('blog.index') }}" class="block px-3 py-2 rounded-xl transition-colors {{ !$categorySlug ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                                All Articles
                            </a>
                        </li>
                        @foreach($categories as $category)
                            <li>
                                <a href="{{ route('blog.index', ['category' => $category->slug]) }}" class="flex items-center justify-between px-3 py-2 rounded-xl transition-colors {{ $categorySlug === $category->slug ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                                    <span>{{ $category->name }}</span>
                                    <span class="text-xs bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full">{{ $category->posts_count }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Posts List -->
            <div class="lg:col-span-3 space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @forelse($posts as $post)
                        <article class="bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                            <div>
                                @if($post->featured_image)
                                    <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gradient-to-br from-emerald-100/60 to-teal-50 flex items-center justify-center text-4xl">
                                        📝
                                    </div>
                                @endif
                                
                                <div class="p-6 space-y-3">
                                    <div class="flex items-center justify-between text-xs font-bold text-slate-400 uppercase">
                                        <span>{{ $post->category?->name ?? 'General' }}</span>
                                        <span>{{ $post->reading_time }} min read</span>
                                    </div>
                                    <h3 class="text-xl font-bold text-slate-900 font-heading leading-tight">
                                        <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-emerald-600 transition-colors">
                                            {{ $post->title }}
                                        </a>
                                    </h3>
                                    <p class="text-slate-500 text-sm leading-relaxed line-clamp-3">
                                        {{ $post->excerpt ?? strip_tags($post->body) }}
                                    </p>
                                </div>
                            </div>

                            <div class="p-6 pt-0 flex justify-between items-center text-xs text-slate-400 border-t border-slate-50 mt-4">
                                <span>By {{ $post->author?->name ?? 'CBTWise Team' }}</span>
                                <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}</span>
                            </div>
                        </article>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <p class="text-slate-500">No blog posts found. Check back soon for study tips!</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
