@extends('layouts.app')

@section('title', __('messages.nav_blog').' — '.setting('site_name'))

@section('content')
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <h1 class="text-4xl font-bold text-center text-gray-900">{{ __('messages.nav_blog') }}</h1>

        <div class="mt-10 flex flex-wrap justify-center gap-3">
            <a href="{{ route('blog.index') }}"
               class="px-4 py-2 rounded-full text-sm {{ ! $activeCategory ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                {{ __('messages.all') }}
            </a>
            @foreach ($categories as $category)
                <a href="{{ route('blog.index', ['category' => $category->slug]) }}"
                   class="px-4 py-2 rounded-full text-sm {{ $activeCategory === $category->slug ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

        <div class="mt-12 grid gap-8 md:grid-cols-3 perspective">
            @forelse ($posts as $i => $post)
                <a href="{{ route('blog.show', $post) }}"
                   data-tilt="6"
                   class="tilt group block bg-white rounded-2xl overflow-hidden border border-gray-100 hover:shadow-2xl transition reveal reveal-delay-{{ ($i % 3) + 1 }}">
                    <div class="aspect-video overflow-hidden bg-gradient-to-br from-brand-700 to-midnight relative">
                        @if ($post->cover_image)
                            <img src="{{ asset('storage/'.$post->cover_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        @else
                            <div class="absolute inset-0 tech-grid opacity-40"></div>
                            <div class="absolute inset-0 grid place-items-center text-white/80 text-2xl font-bold">{{ strtoupper(substr($post->title, 0, 2)) }}</div>
                        @endif
                    </div>
                    <div class="p-6">
                        @if ($post->category)<span class="text-xs font-semibold uppercase tracking-wide text-accent-600">{{ $post->category->name }}</span>@endif
                        <h3 class="mt-1 font-semibold text-gray-900 group-hover:text-brand-600 transition">{{ $post->title }}</h3>
                        <p class="mt-2 text-sm text-gray-500">{{ $post->excerpt }}</p>
                        <p class="mt-3 text-xs text-gray-400">{{ optional($post->published_at)->translatedFormat('d F Y') }}</p>
                    </div>
                </a>
            @empty
                <p class="col-span-full text-center text-gray-500">—</p>
            @endforelse
        </div>

        <div class="mt-12">{{ $posts->links() }}</div>
    </section>
@endsection
