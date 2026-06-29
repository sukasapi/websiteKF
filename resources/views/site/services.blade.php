@extends('layouts.app')

@section('title', __('messages.nav_services').' — '.setting('site_name'))

@section('content')
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <h1 class="text-4xl font-bold text-center text-gray-900">{{ __('messages.our_services') }}</h1>

        <div class="mt-14 grid gap-8 md:grid-cols-2 perspective">
            @foreach ($services as $i => $service)
                <div data-tilt="6" class="tilt group relative p-8 rounded-2xl bg-white border border-gray-100 shadow-sm hover:shadow-xl transition reveal reveal-delay-{{ ($i % 3) + 1 }}">
                    <div class="absolute inset-x-0 -top-px h-1 rounded-t-2xl bg-gradient-to-r from-brand-600 to-accent-500 opacity-0 group-hover:opacity-100 transition"></div>
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-brand-600 to-accent-500 text-white grid place-items-center text-lg font-bold shadow-lg translate-z-8">
                        {{ strtoupper(substr($service->title, 0, 1)) }}
                    </div>
                    <span class="mt-5 inline-block text-xs font-semibold uppercase tracking-wide text-accent-600">{{ ucfirst($service->type) }}</span>
                    <h3 class="mt-2 text-2xl font-semibold text-gray-900">{{ $service->title }}</h3>
                    <p class="mt-3 text-gray-600 leading-relaxed">{{ $service->description }}</p>
                    @if ($service->type === 'animation' && $service->external_url)
                        <a href="{{ $service->external_url }}" target="_blank" rel="noopener"
                           class="shine mt-5 inline-flex items-center gap-1 px-5 py-2.5 rounded-lg bg-brand-600 text-white text-sm font-medium hover:bg-brand-700 transition">
                            {{ __('messages.visit_studio') }} &rarr;
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    </section>

    @include('site.partials.cta')
@endsection
