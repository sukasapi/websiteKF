<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', setting('site_name', 'Kurnia Fedora'))</title>
    <meta name="description" content="@yield('meta_description', app()->getLocale() === 'id' ? setting('site_tagline_id') : setting('site_tagline_en'))">

    {{-- Open Graph --}}
    <meta property="og:title" content="@yield('title', setting('site_name', 'Kurnia Fedora'))">
    <meta property="og:description" content="@yield('meta_description', '')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white text-gray-800 antialiased font-sans">

    @php($locale = app()->getLocale())

    {{-- ===== Header ===== --}}
    <header x-data="{ open: false }" class="sticky top-0 z-50 bg-white/80 backdrop-blur-xl border-b border-gray-100">
        <div class="h-1 bg-gradient-to-r from-brand-600 via-accent-500 to-brand-600"></div>
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-2 font-bold text-lg">
                    @if(setting('site_logo'))
                        <img src="{{ asset('storage/'.setting('site_logo')) }}" alt="logo" class="h-9 w-auto">
                    @else
                        <img src="{{ asset('images/kf-logo.svg') }}" alt="{{ setting('site_name', 'Kurnia Fedora') }}" class="h-9 w-9 shadow-md rounded-xl">
                    @endif
                    <span class="text-gradient-brand">{{ setting('site_name', 'Kurnia Fedora') }}</span>
                </a>

                {{-- Desktop menu --}}
                <div class="hidden md:flex items-center gap-6 text-sm font-medium">
                    @foreach ([
                        'home' => __('messages.nav_home'),
                        'about' => __('messages.nav_about'),
                        'services' => __('messages.nav_services'),
                        'portfolio.index' => __('messages.nav_portfolio'),
                        'blog.index' => __('messages.nav_blog'),
                    ] as $routeName => $label)
                        <a href="{{ route($routeName) }}"
                           class="hover:text-indigo-600 transition {{ request()->routeIs($routeName) ? 'text-indigo-600' : 'text-gray-600' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                    <a href="{{ route('contact.index') }}" class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition">
                        {{ __('messages.nav_contact') }}
                    </a>

                    {{-- Language switcher --}}
                    <div class="flex items-center gap-1 text-xs border border-gray-200 rounded-lg overflow-hidden">
                        <a href="{{ route('locale.switch', 'id') }}" class="px-2 py-1 {{ $locale === 'id' ? 'bg-indigo-600 text-white' : 'text-gray-500' }}">ID</a>
                        <a href="{{ route('locale.switch', 'en') }}" class="px-2 py-1 {{ $locale === 'en' ? 'bg-indigo-600 text-white' : 'text-gray-500' }}">EN</a>
                    </div>
                </div>

                {{-- Mobile toggle --}}
                <button @click="open = !open" class="md:hidden p-2 text-gray-600" aria-label="Menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>

            {{-- Mobile menu --}}
            <div x-show="open" x-cloak class="md:hidden pb-4 space-y-2 text-sm">
                @foreach ([
                    'home' => __('messages.nav_home'),
                    'about' => __('messages.nav_about'),
                    'services' => __('messages.nav_services'),
                    'portfolio.index' => __('messages.nav_portfolio'),
                    'blog.index' => __('messages.nav_blog'),
                    'contact.index' => __('messages.nav_contact'),
                ] as $routeName => $label)
                    <a href="{{ route($routeName) }}" class="block py-2 text-gray-700 hover:text-indigo-600">{{ $label }}</a>
                @endforeach
                <div class="flex gap-2 pt-2">
                    <a href="{{ route('locale.switch', 'id') }}" class="px-3 py-1 rounded border {{ $locale === 'id' ? 'bg-indigo-600 text-white' : '' }}">ID</a>
                    <a href="{{ route('locale.switch', 'en') }}" class="px-3 py-1 rounded border {{ $locale === 'en' ? 'bg-indigo-600 text-white' : '' }}">EN</a>
                </div>
            </div>
        </nav>
    </header>

    {{-- ===== Content ===== --}}
    <main>
        @yield('content')
    </main>

    {{-- ===== Footer ===== --}}
    <footer class="relative bg-midnight text-gray-300 mt-20 overflow-hidden">
        <div class="h-1 bg-gradient-to-r from-brand-600 via-accent-500 to-brand-600"></div>
        <div class="blob absolute -top-24 right-10 w-72 h-72 bg-brand-700/50"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid gap-8 md:grid-cols-3 relative">
            <div>
                <div class="font-bold text-xl mb-3 text-gradient">{{ setting('site_name', 'Kurnia Fedora') }}</div>
                <p class="text-sm text-gray-400">{{ __('messages.footer_about') }}</p>
            </div>
            <div>
                <div class="font-semibold text-white mb-3">{{ __('messages.quick_links') }}</div>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('about') }}" class="hover:text-white">{{ __('messages.nav_about') }}</a></li>
                    <li><a href="{{ route('services') }}" class="hover:text-white">{{ __('messages.nav_services') }}</a></li>
                    <li><a href="{{ route('portfolio.index') }}" class="hover:text-white">{{ __('messages.nav_portfolio') }}</a></li>
                    <li><a href="{{ route('blog.index') }}" class="hover:text-white">{{ __('messages.nav_blog') }}</a></li>
                </ul>
            </div>
            <div>
                <div class="font-semibold text-white mb-3">{{ __('messages.get_in_touch') }}</div>
                <ul class="space-y-2 text-sm text-gray-400">
                    @if(setting('contact_email'))<li>{{ setting('contact_email') }}</li>@endif
                    @if(setting('contact_phone'))<li>{{ setting('contact_phone') }}</li>@endif
                    @if(setting('address'))<li>{{ setting('address') }}</li>@endif
                </ul>
                <div class="flex gap-3 mt-4">
                    @if(setting('social_instagram'))<a href="{{ setting('social_instagram') }}" class="hover:text-white" target="_blank" rel="noopener">Instagram</a>@endif
                    @if(setting('social_linkedin'))<a href="{{ setting('social_linkedin') }}" class="hover:text-white" target="_blank" rel="noopener">LinkedIn</a>@endif
                </div>
            </div>
        </div>
        <div class="border-t border-gray-800 py-4 text-center text-xs text-gray-500">
            &copy; {{ date('Y') }} {{ setting('site_name', 'Kurnia Fedora') }}. {{ __('messages.all_rights') }}
        </div>
    </footer>

</body>
</html>
