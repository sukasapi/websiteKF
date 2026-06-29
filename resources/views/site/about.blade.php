@extends('layouts.app')

@section('title', ($page?->title ?? __('messages.nav_about')).' — '.setting('site_name'))

@section('content')
    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <h1 class="text-4xl font-bold text-gray-900">{{ $page?->title ?? __('messages.nav_about') }}</h1>
        <div class="prose prose-indigo max-w-none mt-8 text-gray-700">
            {!! $page?->content !!}
        </div>
    </section>

    @if ($team->isNotEmpty())
    <section class="bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900">{{ __('messages.our_team') }}</h2>
            <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3 perspective">
                @foreach ($team as $i => $member)
                    <div data-tilt="8" class="tilt group text-center bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl transition p-6 reveal reveal-delay-{{ ($i % 3) + 1 }}">
                        <div class="mx-auto w-28 h-28 rounded-full overflow-hidden ring-4 ring-white shadow-lg bg-gradient-to-br from-brand-600 to-accent-500 grid place-items-center relative">
                            @if ($member->photo)
                                <img src="{{ asset('storage/'.$member->photo) }}" alt="{{ $member->name }}" class="absolute inset-0 w-full h-full object-cover">
                            @else
                                <span class="text-white text-2xl font-bold">{{ strtoupper(substr($member->name, 0, 2)) }}</span>
                            @endif
                        </div>
                        <h3 class="mt-4 font-semibold text-gray-900">{{ $member->name }}</h3>
                        <p class="text-sm text-accent-600">{{ $member->position }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @include('site.partials.cta')
@endsection
