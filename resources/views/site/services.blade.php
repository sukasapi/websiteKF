@extends('layouts.app')

@section('title', __('messages.nav_services').' — '.setting('site_name'))

@section('content')
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <h1 class="text-4xl font-bold text-center text-gray-900">{{ __('messages.our_services') }}</h1>

        <div class="mt-14 grid gap-8 md:grid-cols-2">
            @foreach ($services as $service)
                <div class="p-8 rounded-2xl border border-gray-100 shadow-sm">
                    <span class="inline-block text-xs font-semibold uppercase tracking-wide text-indigo-600">{{ ucfirst($service->type) }}</span>
                    <h3 class="mt-2 text-2xl font-semibold text-gray-900">{{ $service->title }}</h3>
                    <p class="mt-3 text-gray-600 leading-relaxed">{{ $service->description }}</p>
                    @if ($service->type === 'animation' && $service->external_url)
                        <a href="{{ $service->external_url }}" target="_blank" rel="noopener"
                           class="mt-5 inline-flex items-center gap-1 px-5 py-2.5 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition">
                            {{ __('messages.visit_studio') }} &rarr;
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    </section>

    @include('site.partials.cta')
@endsection
