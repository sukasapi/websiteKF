@extends('layouts.app')

@section('title', $post->title.' — '.setting('site_name'))
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($post->excerpt ?: $post->body), 150))

@section('content')
    <article class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <a href="{{ route('blog.index') }}" class="text-sm text-indigo-600 hover:underline">&larr; {{ __('messages.nav_blog') }}</a>

        @if ($post->category)<div class="mt-6 text-sm font-medium text-indigo-600">{{ $post->category->name }}</div>@endif
        <h1 class="mt-2 text-4xl font-bold text-gray-900">{{ $post->title }}</h1>
        <p class="mt-3 text-sm text-gray-500">
            {{ __('messages.published_on') }} {{ optional($post->published_at)->translatedFormat('d F Y') }}
            @if ($post->author) · {{ __('messages.by') }} {{ $post->author->name }}@endif
        </p>

        <div class="mt-8 w-full aspect-video rounded-2xl overflow-hidden relative bg-gradient-to-br from-brand-700 to-midnight shadow-xl">
            @if ($post->cover_image)
                <img src="{{ asset('storage/'.$post->cover_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
            @else
                <div class="absolute inset-0 tech-grid opacity-40"></div>
                <div class="absolute inset-0 grid place-items-center text-white/90 text-5xl font-bold tracking-tight">{{ strtoupper(substr($post->title, 0, 2)) }}</div>
            @endif
        </div>

        <div class="prose prose-indigo max-w-none mt-8 text-gray-700">
            {!! $post->body !!}
        </div>

        @if ($related->isNotEmpty())
            <div class="mt-16 border-t border-gray-100 pt-10">
                <h3 class="text-2xl font-bold text-gray-900">{{ __('messages.related_articles') }}</h3>
                <div class="mt-6 grid gap-6 sm:grid-cols-3">
                    @foreach ($related as $rel)
                        <a href="{{ route('blog.show', $rel) }}" class="group block">
                            <h4 class="font-medium text-gray-900 group-hover:text-indigo-600">{{ $rel->title }}</h4>
                            <p class="mt-1 text-xs text-gray-400">{{ optional($rel->published_at)->translatedFormat('d F Y') }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </article>
@endsection
