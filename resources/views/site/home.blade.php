@extends('layouts.app')

@section('content')
    @php($locale = app()->getLocale())

    {{-- ===== Hero — Midnight 3D scene ===== --}}
    <section class="relative overflow-hidden gradient-mesh text-white">
        <div class="absolute inset-0 tech-grid"></div>
        <div class="blob absolute -top-24 -left-24 w-96 h-96 bg-brand-600 float-slow" data-parallax="28"></div>
        <div class="blob absolute top-1/3 -right-20 w-80 h-80 bg-accent-500/70 float" data-parallax="-36"></div>
        <div class="blob absolute -bottom-24 left-1/3 w-80 h-80 bg-brand-700 float-delay" data-parallax="20"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-36">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                {{-- Copy --}}
                <div class="reveal">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium glass text-accent-200">
                        <span class="w-2 h-2 rounded-full bg-accent-400 pulse-glow"></span>
                        {{ setting('site_name', 'Kurnia Fedora') }} · Software House
                    </span>
                    <h1 class="mt-6 text-5xl sm:text-6xl lg:text-7xl font-bold tracking-tight leading-[1.02]">
                        <span class="text-gradient">{{ $locale === 'id' ? setting('hero_title_id') : setting('hero_title_en') }}</span>
                    </h1>
                    <p class="mt-6 text-lg text-slate-300 max-w-xl">
                        {{ $locale === 'id' ? setting('hero_subtitle_id') : setting('hero_subtitle_en') }}
                    </p>
                    <div class="mt-10 flex flex-wrap gap-4">
                        <a href="{{ route('contact.index') }}"
                           class="shine px-6 py-3 rounded-xl bg-accent-500 text-midnight font-semibold hover:bg-accent-400 transition glow-amber">
                            {{ __('messages.get_in_touch') }}
                        </a>
                        <a href="{{ route('portfolio.index') }}"
                           class="px-6 py-3 rounded-xl glass text-white font-medium hover:bg-white/10 transition">
                            {{ __('messages.nav_portfolio') }} &rarr;
                        </a>
                    </div>
                </div>

                {{-- 3D floating code window --}}
                <div class="relative perspective reveal reveal-delay-2 hidden sm:block">
                    <div class="relative mx-auto max-w-md" data-parallax="-16">
                        <div class="absolute -inset-10 rounded-full border border-white/10 spin-slow"></div>
                        <div class="absolute -inset-2 rounded-3xl bg-gradient-to-br from-brand-500/40 to-accent-500/40 blur-2xl"></div>

                        <div data-tilt="12" class="tilt relative rounded-2xl glass glow-brand p-1 shadow-2xl">
                            <div class="rounded-xl bg-midnight/90 overflow-hidden">
                                <div class="flex items-center gap-1.5 px-4 py-3 border-b border-white/10">
                                    <span class="w-3 h-3 rounded-full bg-red-400/80"></span>
                                    <span class="w-3 h-3 rounded-full bg-accent-400/80"></span>
                                    <span class="w-3 h-3 rounded-full bg-emerald-400/80"></span>
                                    <span class="ml-3 text-xs text-slate-400 font-mono">kurniafedora.app</span>
                                </div>
                                <pre class="px-5 py-5 text-[13px] leading-relaxed font-mono text-slate-300 overflow-hidden"><span class="text-brand-300">const</span> <span class="text-accent-300">studio</span> = {
  craft: <span class="text-emerald-300">'web · mobile · 3D'</span>,
  stack: [<span class="text-emerald-300">'Laravel'</span>, <span class="text-emerald-300">'Vue'</span>, <span class="text-emerald-300">'AI'</span>],
  ship: <span class="text-brand-300">async</span> () => <span class="text-accent-300">launch</span>(),
};
<span class="text-accent-300">studio</span>.ship();<span class="inline-block w-2 h-4 -mb-0.5 bg-accent-400 pulse-glow"></span></pre>
                            </div>
                        </div>

                        <div class="absolute -right-6 top-8 px-3 py-2 rounded-xl glass text-xs font-semibold float glow-amber">⚡ 100% Custom</div>
                        <div class="absolute -left-6 bottom-10 px-3 py-2 rounded-xl glass text-xs font-semibold float-delay">🚀 Fast Delivery</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-b from-transparent to-[#fbfaf7]"></div>
    </section>

    {{-- ===== Stats ===== --}}
    @include('site.partials.stats')

    {{-- ===== Tech-stack marquee ===== --}}
    <section class="border-b border-gray-100 bg-white/60">
        <div class="marquee-group max-w-full overflow-hidden py-6">
            <div class="marquee gap-12 px-6 text-sm font-semibold uppercase tracking-wider text-gray-400">
                @php($stack = ['Laravel','Vue.js','React','Tailwind','Node.js','Flutter','PHP','TypeScript','Figma','3D / WebGL','AI / ML','PostgreSQL'])
                @foreach (array_merge($stack, $stack) as $tech)
                    <span class="flex items-center gap-2 whitespace-nowrap">
                        <span class="w-1.5 h-1.5 rounded-full bg-accent-500"></span>{{ $tech }}
                    </span>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== Services — large alternating rows ===== --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="text-center max-w-2xl mx-auto reveal">
            <span class="text-sm font-semibold uppercase tracking-wider text-accent-600">{{ __('messages.services_eyebrow') }}</span>
            <h2 class="mt-2 text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900">{{ __('messages.our_services') }}</h2>
            <p class="mt-4 text-gray-600">{{ __('messages.services_subtitle') }}</p>
        </div>

        <div class="mt-16 space-y-20 lg:space-y-28">
            @foreach ($services as $i => $service)
                <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
                    {{-- Visual --}}
                    <div class="reveal perspective {{ $i % 2 === 1 ? 'lg:order-2' : '' }}">
                        <div data-tilt="8" class="tilt relative aspect-[4/3] rounded-3xl overflow-hidden gradient-mesh glow-brand">
                            <div class="absolute inset-0 tech-grid"></div>
                            <div class="absolute inset-0 grid place-items-center">
                                <span class="text-[7rem] leading-none font-bold text-white/90 drop-shadow-lg">{{ strtoupper(substr($service->title, 0, 1)) }}</span>
                            </div>
                            <div class="absolute left-5 bottom-5 px-3 py-1.5 rounded-lg glass text-xs font-semibold text-white float">{{ ucfirst($service->type) }}</div>
                        </div>
                    </div>

                    {{-- Text --}}
                    <div class="reveal reveal-delay-1 {{ $i % 2 === 1 ? 'lg:order-1' : '' }}">
                        <span class="text-sm font-semibold uppercase tracking-wider text-accent-600">0{{ $i + 1 }}</span>
                        <h3 class="mt-2 text-2xl sm:text-3xl font-bold text-gray-900">{{ $service->title }}</h3>
                        <p class="mt-4 text-gray-600 leading-relaxed text-lg">{{ $service->description }}</p>
                        @if ($service->type === 'animation' && $service->external_url)
                            <a href="{{ $service->external_url }}" target="_blank" rel="noopener"
                               class="mt-6 inline-flex items-center gap-1 px-5 py-2.5 rounded-lg bg-brand-600 text-white text-sm font-medium hover:bg-brand-700 hover:gap-2 transition-all">
                                {{ __('messages.visit_studio') }} &rarr;
                            </a>
                        @else
                            <a href="{{ route('services') }}"
                               class="mt-6 inline-flex items-center gap-1 text-brand-600 font-medium hover:gap-2 transition-all">
                                {{ __('messages.learn_more') }} &rarr;
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ===== Why choose us ===== --}}
    <section class="bg-gray-50 border-y border-gray-100 py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto reveal">
                <span class="text-sm font-semibold uppercase tracking-wider text-accent-600">{{ __('messages.why_eyebrow') }}</span>
                <h2 class="mt-2 text-3xl sm:text-4xl font-bold text-gray-900">{{ __('messages.why_title') }}</h2>
                <p class="mt-4 text-gray-600">{{ __('messages.why_subtitle') }}</p>
            </div>

            <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-4 perspective">
                @php($whyIcons = ['🎯','👥','⚙️','🛟'])
                @foreach ([1,2,3,4] as $n)
                    <div data-tilt="6" class="tilt group reveal reveal-delay-{{ ($n % 3) + 1 }} relative h-full flex flex-col p-7 rounded-2xl bg-white border border-gray-100 shadow-sm hover:shadow-xl transition">
                        <div class="absolute inset-x-0 -top-px h-1 rounded-t-2xl bg-gradient-to-r from-brand-600 to-accent-500 opacity-0 group-hover:opacity-100 transition"></div>
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-brand-600 to-accent-500 grid place-items-center text-xl shadow-md">{{ $whyIcons[$n-1] }}</div>
                        <h3 class="mt-5 text-lg font-semibold text-gray-900">{{ __('messages.why_'.$n.'_title') }}</h3>
                        <p class="mt-2 text-gray-600 text-sm leading-relaxed flex-grow">{{ __('messages.why_'.$n.'_desc') }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== Featured Portfolio ===== --}}
    @if ($featuredProjects->isNotEmpty())
    <section class="py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl sm:text-4xl font-bold text-center text-gray-900 reveal">{{ __('messages.featured_work') }}</h2>
            <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3 perspective">
                @foreach ($featuredProjects as $i => $project)
                    <a href="{{ route('portfolio.show', $project) }}"
                       data-tilt="7"
                       class="tilt group block bg-white rounded-2xl overflow-hidden border border-gray-100 hover:shadow-2xl transition reveal reveal-delay-{{ ($i % 3) + 1 }}">
                        <div class="aspect-video overflow-hidden bg-gradient-to-br from-brand-600 via-brand-800 to-midnight relative">
                            @if ($project->cover_image)
                                <img src="{{ asset('storage/'.$project->cover_image) }}" alt="{{ $project->title }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                            @else
                                <div class="absolute inset-0 grid place-items-center text-white/90 text-3xl font-bold tracking-tight">{{ strtoupper(substr($project->title, 0, 2)) }}</div>
                                <div class="absolute inset-0 tech-grid opacity-40"></div>
                            @endif
                        </div>
                        <div class="p-6">
                            @if ($project->category)<span class="text-xs font-semibold uppercase tracking-wide text-accent-600">{{ $project->category->name }}</span>@endif
                            <h3 class="mt-1 font-semibold text-gray-900 group-hover:text-brand-600 transition">{{ $project->title }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="text-center mt-10">
                <a href="{{ route('portfolio.index') }}" class="inline-flex items-center gap-1 text-brand-600 font-medium hover:gap-2 transition-all">{{ __('messages.view_all_portfolio') }} &rarr;</a>
            </div>
        </div>
    </section>
    @endif

    {{-- ===== How we work ===== --}}
    <section class="bg-gray-50 border-y border-gray-100 py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto reveal">
                <span class="text-sm font-semibold uppercase tracking-wider text-accent-600">{{ __('messages.process_eyebrow') }}</span>
                <h2 class="mt-2 text-3xl sm:text-4xl font-bold text-gray-900">{{ __('messages.process_title') }}</h2>
            </div>
            <div class="mt-14 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([1,2,3,4] as $n)
                    <div class="relative reveal reveal-delay-{{ ($n % 3) + 1 }}">
                        <div class="text-5xl font-bold text-transparent" style="-webkit-text-stroke:1.5px #1a5fb4;">0{{ $n }}</div>
                        <h3 class="mt-4 text-lg font-semibold text-gray-900">{{ __('messages.process_'.$n.'_title') }}</h3>
                        <p class="mt-2 text-gray-600 text-sm leading-relaxed">{{ __('messages.process_'.$n.'_desc') }}</p>
                        @if ($n < 4)
                            <div class="hidden lg:block absolute top-6 -right-4 text-accent-500 text-2xl">→</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== Latest Articles ===== --}}
    @if ($latestPosts->isNotEmpty())
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <h2 class="text-3xl sm:text-4xl font-bold text-center text-gray-900 reveal">{{ __('messages.latest_articles') }}</h2>
        <div class="mt-12 grid gap-8 md:grid-cols-3">
            @foreach ($latestPosts as $i => $post)
                <a href="{{ route('blog.show', $post) }}" class="group block reveal reveal-delay-{{ ($i % 3) + 1 }}">
                    <div class="aspect-video rounded-xl overflow-hidden bg-gradient-to-br from-brand-700 to-midnight relative">
                        @if ($post->cover_image)
                            <img src="{{ asset('storage/'.$post->cover_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="absolute inset-0 tech-grid opacity-40"></div>
                            <div class="absolute inset-0 grid place-items-center text-white/80 text-2xl font-bold">{{ strtoupper(substr($post->title, 0, 2)) }}</div>
                        @endif
                    </div>
                    <h3 class="mt-4 font-semibold text-gray-900 group-hover:text-brand-600 transition">{{ $post->title }}</h3>
                    <p class="mt-2 text-sm text-gray-500">{{ $post->excerpt }}</p>
                </a>
            @endforeach
        </div>
        <div class="text-center mt-10">
            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-1 text-brand-600 font-medium hover:gap-2 transition-all">{{ __('messages.view_all_articles') }} &rarr;</a>
        </div>
    </section>
    @endif

    @include('site.partials.cta')
@endsection
