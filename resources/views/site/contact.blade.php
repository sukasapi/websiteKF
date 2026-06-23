@extends('layouts.app')

@section('title', __('messages.contact_title').' — '.setting('site_name'))

@section('content')
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid gap-12 lg:grid-cols-2">
            {{-- Info --}}
            <div>
                <h1 class="text-4xl font-bold text-gray-900">{{ __('messages.contact_title') }}</h1>
                <p class="mt-3 text-gray-600">{{ __('messages.contact_subtitle') }}</p>

                <dl class="mt-8 space-y-4 text-sm">
                    @if (setting('contact_email'))
                        <div><dt class="font-semibold text-gray-900">{{ __('messages.form_email') }}</dt><dd class="text-gray-600">{{ setting('contact_email') }}</dd></div>
                    @endif
                    @if (setting('contact_phone'))
                        <div><dt class="font-semibold text-gray-900">{{ __('messages.phone') }}</dt><dd class="text-gray-600">{{ setting('contact_phone') }}</dd></div>
                    @endif
                    @if (setting('address'))
                        <div><dt class="font-semibold text-gray-900">{{ __('messages.address') }}</dt><dd class="text-gray-600">{{ setting('address') }}</dd></div>
                    @endif
                </dl>

                @if (setting('whatsapp'))
                    <a href="https://wa.me/{{ setting('whatsapp') }}" target="_blank" rel="noopener"
                       class="mt-6 inline-block px-5 py-2.5 rounded-lg bg-green-600 text-white text-sm font-medium hover:bg-green-700 transition">
                        WhatsApp
                    </a>
                @endif
            </div>

            {{-- Form --}}
            <div class="p-8 rounded-2xl border border-gray-100 shadow-sm">
                @if (session('success'))
                    <div class="mb-6 p-4 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('contact.store') }}" class="space-y-5">
                    @csrf

                    {{-- Honeypot --}}
                    <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off">

                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('messages.form_name') }}</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="mt-1 w-full rounded-lg border-gray-300 border px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('messages.form_email') }}</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="mt-1 w-full rounded-lg border-gray-300 border px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('messages.form_subject') }}</label>
                        <input type="text" name="subject" value="{{ old('subject') }}"
                               class="mt-1 w-full rounded-lg border-gray-300 border px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('messages.form_message') }}</label>
                        <textarea name="message" rows="5" required
                                  class="mt-1 w-full rounded-lg border-gray-300 border px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('message') }}</textarea>
                        @error('message')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="w-full px-6 py-3 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition">
                        {{ __('messages.form_send') }}
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection
