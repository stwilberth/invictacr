@props(['items' => [], 'title' => 'Preguntas Frecuentes'])

@if(!empty($items))
@push('json-ld')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "FAQPage",
    "mainEntity": [
        @foreach($items as $item)
        {
            "@@type": "Question",
            "name": {!! json_encode($item['q']) !!},
            "acceptedAnswer": {
                "@@type": "Answer",
                "text": {!! json_encode(strip_tags($item['a'])) !!}
            }
        }{{ !$loop->last ? ',' : '' }}
        @endforeach
    ]
}
</script>
@endpush

<section class="max-w-3xl mx-auto mt-14 md:mt-20 px-4">
    <h2 class="text-2xl md:text-3xl font-black uppercase tracking-tight text-gray-900 dark:text-white text-center mb-8">{{ $title }}</h2>
    <div class="space-y-3">
        @foreach($items as $item)
        <details class="group bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
            <summary class="flex items-center justify-between gap-4 px-5 py-4 cursor-pointer list-none">
                <span class="text-sm md:text-base font-bold text-gray-900 dark:text-white">{{ $item['q'] }}</span>
                <i class="fa-solid fa-chevron-down text-[#00C4FF] transition-transform group-open:rotate-180 shrink-0"></i>
            </summary>
            <div class="px-5 pb-5 text-sm leading-relaxed text-gray-600 dark:text-gray-300">
                {!! $item['a'] !!}
            </div>
        </details>
        @endforeach
    </div>
</section>
@endif
