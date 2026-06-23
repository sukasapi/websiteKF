<section class="bg-indigo-600">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
        <h2 class="text-3xl font-bold text-white">{{ __('messages.cta_title') }}</h2>
        <p class="mt-3 text-indigo-100">{{ __('messages.cta_subtitle') }}</p>
        <a href="{{ route('contact.index') }}" class="mt-8 inline-block px-6 py-3 rounded-lg bg-white text-indigo-600 font-medium hover:bg-indigo-50 transition">
            {{ __('messages.get_in_touch') }}
        </a>
    </div>
</section>
