<section class="relative overflow-hidden gradient-mesh text-white">
    <div class="absolute inset-0 tech-grid"></div>
    <div class="blob absolute -top-16 left-1/4 w-72 h-72 bg-accent-500/60 float"></div>
    <div class="blob absolute -bottom-20 right-1/4 w-72 h-72 bg-brand-600 float-delay"></div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
        <div class="perspective">
            <div data-tilt="6" class="tilt inline-block glass rounded-3xl px-8 sm:px-14 py-12 reveal">
                <h2 class="text-3xl sm:text-4xl font-bold">
                    <span class="text-gradient">{{ __('messages.cta_title') }}</span>
                </h2>
                <p class="mt-4 text-slate-300 max-w-xl mx-auto">{{ __('messages.cta_subtitle') }}</p>
                <a href="{{ route('contact.index') }}"
                   class="shine mt-8 inline-block px-7 py-3 rounded-xl bg-accent-500 text-midnight font-semibold hover:bg-accent-400 transition glow-amber">
                    {{ __('messages.get_in_touch') }}
                </a>
            </div>
        </div>
    </div>
</section>
