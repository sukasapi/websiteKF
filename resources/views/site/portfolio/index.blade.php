@extends('layouts.app')

@section('title', __('messages.nav_portfolio').' — '.setting('site_name'))

@section('content')
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <h1 class="text-4xl font-bold text-center text-gray-900">{{ __('messages.nav_portfolio') }}</h1>

        {{-- Filter kategori --}}
        <div class="mt-10 flex flex-wrap justify-center gap-3">
            <a href="{{ route('portfolio.index') }}"
               class="px-4 py-2 rounded-full text-sm {{ ! $activeCategory ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                {{ __('messages.all') }}
            </a>
            @foreach ($categories as $category)
                <a href="{{ route('portfolio.index', ['category' => $category->slug]) }}"
                   class="px-4 py-2 rounded-full text-sm {{ $activeCategory === $category->slug ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

        <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($projects as $project)
                <a href="{{ route('portfolio.show', $project) }}" class="group block bg-white rounded-2xl overflow-hidden border border-gray-100 hover:shadow-lg transition">
                    <div class="aspect-video bg-gray-100 overflow-hidden">
                        @if ($project->cover_image)
                            <img src="{{ asset('storage/'.$project->cover_image) }}" alt="{{ $project->title }}" class="w-full h-full object-cover group-hover:scale-105 transition">
                        @endif
                    </div>
                    <div class="p-6">
                        @if ($project->category)<span class="text-xs font-medium text-indigo-600">{{ $project->category->name }}</span>@endif
                        <h3 class="mt-1 font-semibold text-gray-900">{{ $project->title }}</h3>
                        <p class="mt-1 text-sm text-gray-500">{{ $project->client }} @if($project->year)· {{ $project->year }}@endif</p>
                    </div>
                </a>
            @empty
                <p class="col-span-full text-center text-gray-500">—</p>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $projects->links() }}
        </div>
    </section>
@endsection
