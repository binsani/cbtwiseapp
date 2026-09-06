@extends('layouts.public')

@section('title', $post->title . ' — CBTWise Blog')
@section('meta_description', $post->meta_description ?? $post->excerpt ?? 'Read this detailed study guide from the CBTWise academic team.')

@section('json_ld')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "BlogPosting",
  "headline": "{{ $post->title }}",
  "description": "{{ $post->meta_description ?? $post->excerpt }}",
  "image": "{{ $post->featured_image ?? url('/logo.png') }}",
  "datePublished": "{{ $post->published_at ? $post->published_at->toIso8601String() : $post->created_at->toIso8601String() }}",
  "author": {
    "@@type": "Person",
    "name": "{{ $post->author?->name ?? 'CBTWise Academic Team' }}"
  },
  "publisher": {
    "@@type": "EducationalOrganization",
    "name": "CBTWise",
    "logo": {
      "@@type": "ImageObject",
      "url": "{{ url('/logo.png') }}"
    }
  }
}
</script>
@endsection

@section('content')
<article class="py-20 bg-slate-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
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
                        <a href="{{ route('blog.index') }}" class="ml-1 hover:text-emerald-600 transition-colors md:ml-2">Blog</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-slate-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ml-1 text-slate-400 md:ml-2 truncate max-w-[200px]">{{ $post->title }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Main Card -->
        <div class="bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-sm">
            @if($post->featured_image)
                <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-80 object-cover">
            @else
                <div class="w-full h-48 bg-gradient-to-br from-emerald-100/60 to-teal-50 flex items-center justify-center text-5xl">
                    📚
                </div>
            @endif

            <div class="p-8 sm:p-12 space-y-6">
                <div class="flex items-center gap-3 text-xs font-bold text-slate-400 uppercase">
                    <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded border border-emerald-100">{{ $post->category?->name ?? 'General' }}</span>
                    <span>•</span>
                    <span>{{ $post->reading_time }} min read</span>
                    <span>•</span>
                    <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}</span>
                </div>

                <h1 class="text-3xl sm:text-4xl font-black text-slate-950 font-heading tracking-tight leading-tight">
                    {{ $post->title }}
                </h1>

                <!-- Author details -->
                <div class="flex items-center gap-3 pb-6 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 text-slate-700 flex items-center justify-center font-bold text-sm">
                        👤
                    </div>
                    <div>
                        <div class="text-sm font-bold text-slate-900">{{ $post->author?->name ?? 'CBTWise Scholar' }}</div>
                        <div class="text-xs text-slate-400">Contributor / Academic Team</div>
                    </div>
                </div>

                <!-- Body content -->
                <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed text-sm space-y-4">
                    {!! $post->rendered_body !!}
                </div>
            </div>
        </div>

        <!-- Related posts -->
        @if($relatedPosts->isNotEmpty())
            <div class="mt-16 space-y-6">
                <h3 class="text-2xl font-black text-slate-900 font-heading">Related Articles</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedPosts as $relPost)
                        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-between">
                            <div>
                                <span class="text-xs font-bold text-emerald-600 uppercase">{{ $relPost->category?->name ?? 'General' }}</span>
                                <h4 class="font-bold text-slate-900 mt-2 font-heading text-sm line-clamp-2">
                                    <a href="{{ route('blog.show', $relPost->slug) }}" class="hover:text-emerald-600 transition-colors">
                                        {{ $relPost->title }}
                                    </a>
                                </h4>
                            </div>
                            <span class="text-xs text-slate-400 mt-4 block">
                                {{ $relPost->reading_time }} min read
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</article>
@endsection
