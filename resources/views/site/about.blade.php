@extends('layouts.app')

@section('title', ($page?->title ?? __('messages.nav_about')).' — '.setting('site_name'))

@section('content')
    @php($locale = app()->getLocale())

    {{-- ===== About hero (midnight) ===== --}}
    <section class="relative overflow-hidden gradient-mesh text-white">
        <div class="absolute inset-0 tech-grid"></div>
        <div class="blob absolute -top-24 right-0 w-96 h-96 bg-brand-600 float-slow" data-parallax="24"></div>
        <div class="blob absolute -bottom-24 -left-10 w-80 h-80 bg-accent-500/60 float" data-parallax="-28"></div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32 text-center reveal">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium glass text-accent-200">
                <span class="w-2 h-2 rounded-full bg-accent-400 pulse-glow"></span>
                {{ __('messages.nav_about') }}
            </span>
            <h1 class="mt-6 text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight">
                <span class="text-gradient">{{ $page?->title ?? __('messages.nav_about') }}</span>
            </h1>
            <p class="mt-5 text-lg text-slate-300 max-w-2xl mx-auto">
                {{ $locale === 'id' ? setting('site_tagline_id') : setting('site_tagline_en') }}
            </p>
        </div>

        <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-b from-transparent to-[#fbfaf7]"></div>
    </section>

    {{-- ===== Story / content ===== --}}
    @if ($page?->content)
    <section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-20 reveal">
        <div class="prose prose-indigo max-w-none text-gray-700 prose-headings:text-gray-900 prose-a:text-brand-600">
            {!! $page?->content !!}
        </div>
    </section>
    @endif

    {{-- ===== Stats ===== --}}
    @include('site.partials.stats')

    {{-- ===== Team ===== --}}
    @if ($team->isNotEmpty())
    <section class="py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto reveal">
                <span class="text-sm font-semibold uppercase tracking-wider text-accent-600">{{ setting('site_name', 'Kurnia Fedora') }}</span>
                <h2 class="mt-2 text-3xl sm:text-4xl font-bold text-gray-900">{{ __('messages.our_team') }}</h2>
            </div>
            <div class="mt-14 grid gap-8 sm:grid-cols-2 lg:grid-cols-3 perspective">
                @foreach ($team as $i => $member)
                    <div data-tilt="8" class="tilt group text-center bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl transition p-8 reveal reveal-delay-{{ ($i % 3) + 1 }}">
                        <div class="mx-auto w-32 h-32 rounded-full overflow-hidden ring-4 ring-white shadow-lg bg-gradient-to-br from-brand-600 to-accent-500 grid place-items-center relative">
                            @if ($member->photo)
                                <img src="{{ asset('storage/'.$member->photo) }}" alt="{{ $member->name }}" class="absolute inset-0 w-full h-full object-cover">
                            @else
                                <span class="text-white text-3xl font-bold">{{ strtoupper(substr($member->name, 0, 2)) }}</span>
                            @endif
                        </div>
                        <h3 class="mt-5 font-semibold text-gray-900 text-lg">{{ $member->name }}</h3>
                        <p class="text-sm text-accent-600">{{ $member->position }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @include('site.partials.cta')
@endsection
