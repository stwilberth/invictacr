@props(['title', 'highlight' => null, 'subtitle' => null])

@php
    $before = $title;
    $after = '';
    if ($highlight) {
        $pos = strpos($title, $highlight);
        if ($pos !== false) {
            $before = substr($title, 0, $pos);
            $after = substr($title, $pos + strlen($highlight));
        }
    }
@endphp

<div class="text-center py-4 md:py-8 px-4">
    <h1 class="text-2xl md:text-4xl font-black uppercase italic tracking-tighter text-slate-900 dark:text-white leading-none">
        @if($highlight)
            {{ $before }}<span class="text-[#00C4FF]">{{ $highlight }}</span>{{ $after }}
        @else
            {{ $title }}
        @endif
    </h1>
    @if($subtitle)
        <p class="mt-4 text-sm md:text-base text-slate-500 dark:text-gray-400 max-w-2xl mx-auto leading-relaxed">
            {{ $subtitle }}
        </p>
    @endif
</div>
