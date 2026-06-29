@php
    $items = [
        ['value' => $stats['projects'] ?? 0, 'label' => __('messages.nav_portfolio'),  'suffix' => '+'],
        ['value' => $stats['services'] ?? 0, 'label' => __('messages.our_services'),    'suffix' => ''],
        ['value' => $stats['team'] ?? 0,     'label' => __('messages.our_team'),         'suffix' => ''],
        ['value' => $stats['posts'] ?? 0,    'label' => __('messages.latest_articles'),  'suffix' => '+'],
    ];
@endphp

<section class="bg-white border-y border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
        @foreach ($items as $i => $item)
            <div class="reveal reveal-delay-{{ ($i % 3) + 1 }}">
                <div class="text-4xl sm:text-5xl font-bold text-gradient-brand">
                    <span data-count="{{ (int) $item['value'] }}">0</span>{{ $item['suffix'] }}
                </div>
                <div class="mt-2 text-xs sm:text-sm font-semibold uppercase tracking-wider text-gray-500">{{ $item['label'] }}</div>
            </div>
        @endforeach
    </div>
</section>
