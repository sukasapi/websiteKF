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
            <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($team as $member)
                    <div class="text-center">
                        <div class="mx-auto w-28 h-28 rounded-full bg-gray-200 overflow-hidden">
                            @if ($member->photo)
                                <img src="{{ asset('storage/'.$member->photo) }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <h3 class="mt-4 font-semibold text-gray-900">{{ $member->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $member->position }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @include('site.partials.cta')
@endsection
