@extends('layouts.app')

@section('title', $project->title.' — '.setting('site_name'))
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($project->description), 150))

@section('content')
    <article class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <a href="{{ route('portfolio.index') }}" class="text-sm text-indigo-600 hover:underline">&larr; {{ __('messages.nav_portfolio') }}</a>

        <h1 class="mt-4 text-4xl font-bold text-gray-900">{{ $project->title }}</h1>

        <div class="mt-4 flex flex-wrap gap-4 text-sm text-gray-500">
            @if ($project->category)<span><strong>{{ __('messages.category') }}:</strong> {{ $project->category->name }}</span>@endif
            @if ($project->client)<span><strong>{{ __('messages.client') }}:</strong> {{ $project->client }}</span>@endif
            @if ($project->year)<span><strong>{{ __('messages.year') }}:</strong> {{ $project->year }}</span>@endif
        </div>

        <div class="mt-8 w-full aspect-video rounded-2xl overflow-hidden relative bg-gradient-to-br from-brand-600 via-brand-800 to-midnight shadow-xl">
            @if ($project->cover_image)
                <img src="{{ asset('storage/'.$project->cover_image) }}" alt="{{ $project->title }}" class="w-full h-full object-cover">
            @else
                <div class="absolute inset-0 tech-grid opacity-40"></div>
                <div class="absolute inset-0 grid place-items-center text-white/90 text-5xl font-bold tracking-tight">{{ strtoupper(substr($project->title, 0, 2)) }}</div>
            @endif
        </div>

        <div class="prose prose-indigo max-w-none mt-8 text-gray-700">
            {!! $project->description !!}
        </div>

        @if ($project->tech_stack)
            <div class="mt-8">
                <h3 class="font-semibold text-gray-900">{{ __('messages.technologies') }}</h3>
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach ($project->tech_stack as $tech)
                        <span class="px-3 py-1 rounded-full bg-gray-100 text-sm text-gray-700">{{ $tech }}</span>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($project->demo_url)
            <a href="{{ $project->demo_url }}" target="_blank" rel="noopener" class="mt-8 inline-block px-6 py-3 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition">
                {{ __('messages.visit_demo') }} &rarr;
            </a>
        @endif

        {{-- Galeri --}}
        @if ($project->images->isNotEmpty())
            <div class="mt-12">
                <h3 class="font-semibold text-gray-900 mb-4">{{ __('messages.gallery') }}</h3>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($project->images as $image)
                        <figure>
                            <img src="{{ asset('storage/'.$image->path) }}" alt="{{ $image->caption }}" class="rounded-xl w-full">
                            @if ($image->caption)<figcaption class="mt-1 text-xs text-gray-500">{{ $image->caption }}</figcaption>@endif
                        </figure>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Related --}}
        @if ($related->isNotEmpty())
            <div class="mt-16">
                <h3 class="text-2xl font-bold text-gray-900">{{ __('messages.related_projects') }}</h3>
                <div class="mt-6 grid gap-6 sm:grid-cols-3">
                    @foreach ($related as $rel)
                        <a href="{{ route('portfolio.show', $rel) }}" class="group block">
                            <div class="aspect-video rounded-xl overflow-hidden relative bg-gradient-to-br from-brand-700 to-midnight">
                                @if ($rel->cover_image)
                                    <img src="{{ asset('storage/'.$rel->cover_image) }}" alt="{{ $rel->title }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                @else
                                    <div class="absolute inset-0 tech-grid opacity-40"></div>
                                    <div class="absolute inset-0 grid place-items-center text-white/80 text-xl font-bold">{{ strtoupper(substr($rel->title, 0, 2)) }}</div>
                                @endif
                            </div>
                            <h4 class="mt-3 font-medium text-gray-900 group-hover:text-brand-600 transition">{{ $rel->title }}</h4>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </article>
@endsection
