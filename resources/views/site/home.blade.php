@extends('layouts.app')

@section('content')
    @php($locale = app()->getLocale())

    {{-- Hero --}}
    <section class="relative bg-gradient-to-br from-indigo-50 via-white to-purple-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32 text-center">
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-gray-900 max-w-4xl mx-auto">
                {{ $locale === 'id' ? setting('hero_title_id') : setting('hero_title_en') }}
            </h1>
            <p class="mt-6 text-lg text-gray-600 max-w-2xl mx-auto">
                {{ $locale === 'id' ? setting('hero_subtitle_id') : setting('hero_subtitle_en') }}
            </p>
            <div class="mt-10 flex flex-wrap justify-center gap-4">
                <a href="{{ route('contact.index') }}" class="px-6 py-3 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition">
                    {{ __('messages.get_in_touch') }}
                </a>
                <a href="{{ route('portfolio.index') }}" class="px-6 py-3 rounded-lg border border-gray-300 text-gray-700 font-medium hover:border-indigo-600 hover:text-indigo-600 transition">
                    {{ __('messages.nav_portfolio') }}
                </a>
            </div>
        </div>
    </section>

    {{-- Services --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <h2 class="text-3xl font-bold text-center text-gray-900">{{ __('messages.our_services') }}</h2>
        <div class="mt-12 grid gap-8 md:grid-cols-2">
            @foreach ($services as $service)
                <div class="p-8 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition">
                    <h3 class="text-xl font-semibold text-gray-900">{{ $service->title }}</h3>
                    <p class="mt-3 text-gray-600">{{ $service->description }}</p>
                    @if ($service->type === 'animation' && $service->external_url)
                        <a href="{{ $service->external_url }}" target="_blank" rel="noopener"
                           class="mt-4 inline-flex items-center gap-1 text-indigo-600 font-medium hover:underline">
                            {{ __('messages.visit_studio') }} &rarr;
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    </section>

    {{-- Featured Portfolio --}}
    @if ($featuredProjects->isNotEmpty())
    <section class="bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900">{{ __('messages.featured_work') }}</h2>
            <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($featuredProjects as $project)
                    <a href="{{ route('portfolio.show', $project) }}" class="group block bg-white rounded-2xl overflow-hidden border border-gray-100 hover:shadow-lg transition">
                        <div class="aspect-video bg-gray-100 overflow-hidden">
                            @if ($project->cover_image)
                                <img src="{{ asset('storage/'.$project->cover_image) }}" alt="{{ $project->title }}" class="w-full h-full object-cover group-hover:scale-105 transition">
                            @endif
                        </div>
                        <div class="p-6">
                            @if ($project->category)<span class="text-xs font-medium text-indigo-600">{{ $project->category->name }}</span>@endif
                            <h3 class="mt-1 font-semibold text-gray-900">{{ $project->title }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="text-center mt-10">
                <a href="{{ route('portfolio.index') }}" class="text-indigo-600 font-medium hover:underline">{{ __('messages.view_all_portfolio') }} &rarr;</a>
            </div>
        </div>
    </section>
    @endif

    {{-- Latest Articles --}}
    @if ($latestPosts->isNotEmpty())
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <h2 class="text-3xl font-bold text-center text-gray-900">{{ __('messages.latest_articles') }}</h2>
        <div class="mt-12 grid gap-8 md:grid-cols-3">
            @foreach ($latestPosts as $post)
                <a href="{{ route('blog.show', $post) }}" class="group block">
                    <div class="aspect-video bg-gray-100 rounded-xl overflow-hidden">
                        @if ($post->cover_image)
                            <img src="{{ asset('storage/'.$post->cover_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition">
                        @endif
                    </div>
                    <h3 class="mt-4 font-semibold text-gray-900 group-hover:text-indigo-600">{{ $post->title }}</h3>
                    <p class="mt-2 text-sm text-gray-500">{{ $post->excerpt }}</p>
                </a>
            @endforeach
        </div>
        <div class="text-center mt-10">
            <a href="{{ route('blog.index') }}" class="text-indigo-600 font-medium hover:underline">{{ __('messages.view_all_articles') }} &rarr;</a>
        </div>
    </section>
    @endif

    @include('site.partials.cta')
@endsection
