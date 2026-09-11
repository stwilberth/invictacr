@props(['galleryItems', 'title'])

@php
    $totalSlides = count($galleryItems);
    $streamSubdomain = config('services.cloudflare.stream_customer_subdomain', 'customer-8ybt5aiee4vaophw');
    $navBase = 'absolute top-1/2 -translate-y-1/2 w-7 h-7 md:w-11 md:h-11 bg-[#1E293B] hover:bg-[#334155] text-white rounded-full shadow-lg flex items-center justify-center transition-all duration-300 z-20';
    $navLeft = 'left-2 md:left-4 ' . $navBase;
    $navRight = 'right-2 md:right-4 ' . $navBase;
    $zoomBtn = 'absolute bottom-3 right-3 md:bottom-4 md:right-4 w-7 h-7 md:w-9 md:h-9 bg-white/95 dark:bg-gray-900/95 border border-gray-200 dark:border-gray-700/80 text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-lg shadow-sm flex items-center justify-center transition-all duration-300 z-30';
    $videoItem = collect($galleryItems)->first(fn($i) => ($i['type'] ?? 'image') !== 'image');
    $videoUid = $videoItem['videoUid'] ?? $videoItem['video_uid'] ?? null;
@endphp

<div x-data='{
    galleryItems: @json($galleryItems),
    currentIndex: 0,
    totalSlides: {{ $totalSlides }},
    touchStartX: null,
    modalTitle: @json($title),
    hasVideo() {
        return this.galleryItems.some((i) => i.type !== "image");
    },
    openModalVideo() {
        const v = this.galleryItems.find((i) => i.type !== "image");
        const uid = v ? (v.videoUid || v.video_uid || null) : null;
        if (!uid || typeof openImageModal !== "function") return;
        const imgItem = this.galleryItems.find((i) => i.type === "image");
        const src = imgItem ? (imgItem.zoomUrl || imgItem.url || "") : "";
        openImageModal(src, this.modalTitle, uid, true);
    },
    goTo(i) {
        this.currentIndex = i;
    },
    onTouchStart(e) {
        this.touchStartX = e.touches[0].clientX;
    },
    onTouchEnd(e) {
        if (this.touchStartX === null) return;
        const dx = e.changedTouches[0].clientX - this.touchStartX;
        this.touchStartX = null;
        if (Math.abs(dx) > 40) {
            if (dx < 0) this.next(); else this.prev();
        }
    },
    prev() {
        this.currentIndex = (this.currentIndex - 1 + this.totalSlides) % this.totalSlides;
    },
    next() {
        this.currentIndex = (this.currentIndex + 1) % this.totalSlides;
    }
}'>
    <div class="relative overflow-hidden group/image w-full aspect-[4/3] rounded-xl bg-white dark:bg-gray-900"
        @touchstart="onTouchStart($event)" @touchend="onTouchEnd($event)">
        <div class="absolute inset-0 flex" :style="`transform: translateX(-${currentIndex * 100}%); transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);`">
            @foreach($galleryItems as $idx => $item)
            <div class="relative w-full h-full flex-shrink-0 flex items-center justify-center">
                @if($item['type'] === 'image')
                    <div class="absolute inset-0 flex items-center justify-center cursor-zoom-in" @click="openImageModal('{{ $item['zoomUrl'] }}', '{{ $title }}', '{{ $videoUid }}')">
                        <img src="{{ $item['url'] }}" alt="{{ $title }} - {{ $idx + 1 }}" class="w-full h-full object-contain transition-transform duration-500 hover:scale-[1.02]" @if($idx === 0) fetchpriority="high" @else loading="lazy" @endif />
                    </div>
                @else
                    <div class="absolute inset-0 flex items-center justify-center cursor-pointer" @click="openModalVideo()">
                        <img src="{{ $item['thumbnail'] ?? $galleryItems[0]['url'] }}" alt="Video del reloj" class="w-full h-full object-cover" loading="lazy" />
                        <div class="absolute inset-0 flex flex-col items-center justify-center gap-1.5">
                            <div class="w-10 h-10 md:w-16 md:h-16 bg-red-600 rounded-full flex items-center justify-center shadow-2xl border-4 border-white/30 hover:border-white/60 transition-all duration-300 hover:scale-110">
                                <i class="fa-solid fa-play text-white text-base md:text-2xl ml-1"></i>
                            </div>
                            <span class="bg-black/60 backdrop-blur-sm text-white text-[9px] font-black uppercase tracking-wider px-2.5 py-1 rounded-full">
                                <i class="fa-solid fa-play text-[8px] mr-1"></i> Ver video
                            </span>
                        </div>
                    </div>
                @endif
            </div>
            @endforeach
        </div>

        {{-- Navigation buttons --}}
        <button type="button" @click="prev()" x-show="totalSlides > 1" class="{{ $navLeft }}" aria-label="Anterior">
            <i class="fa-solid fa-chevron-left text-xs md:text-base"></i>
        </button>
        <button type="button" @click="next()" x-show="totalSlides > 1" class="{{ $navRight }}" aria-label="Siguiente">
            <i class="fa-solid fa-chevron-right text-xs md:text-base"></i>
        </button>

        {{-- Zoom button (only on images) --}}
        <button type="button" x-show="galleryItems[currentIndex]?.type === 'image'" @click="event.preventDefault(); openImageModal(galleryItems[currentIndex].zoomUrl, '{{ $title }}', '{{ $videoUid }}')" class="{{ $zoomBtn }} cursor-pointer">
            <i class="fa-solid fa-expand text-xs md:text-sm"></i>
        </button>
    </div>

    {{-- Thumbnail gallery --}}
    @if($totalSlides > 1)
    <div class="flex items-center gap-2 mt-3">
        <button type="button" @click="prev()" aria-label="Anterior" class="flex-shrink-0 w-7 h-7 md:w-9 md:h-9 flex items-center justify-center rounded-full border border-gray-200 text-gray-500 hover:border-gray-400 hover:text-gray-800 transition-colors">
            <i class="fa-solid fa-chevron-left text-xs"></i>
        </button>
    <div class="flex gap-2 md:gap-2.5 flex-1 justify-start md:justify-center overflow-x-auto scrollbar-hide">
        @foreach($galleryItems as $i => $item)
            @if($item['type'] === 'image')
            <button type="button" @click="goTo({{ $i }})" data-gallery-img="{{ $item['zoomUrl'] }}" class="w-14 h-14 md:w-[84px] md:h-[84px] flex-shrink-0 rounded-[10px] border-2 overflow-hidden bg-gray-50 dark:bg-gray-900 transition-all gallery-thumb"
                :class="currentIndex === {{ $i }} ? 'border-[#0A7CFF]' : 'border-transparent hover:border-gray-300 dark:hover:border-gray-600'">
                <img src="{{ $item['url'] }}" alt="" class="w-full h-full object-contain" loading="lazy" onerror="this.closest('.gallery-thumb').style.display='none'" />
            </button>
            @else
            <button type="button" @click="goTo({{ $i }})" class="w-14 h-14 md:w-[84px] md:h-[84px] flex-shrink-0 rounded-[10px] border-2 overflow-hidden bg-gray-900 transition-all gallery-thumb relative"
                :class="currentIndex === {{ $i }} ? 'border-[#0A7CFF]' : 'border-transparent hover:border-gray-300 dark:hover:border-gray-600'">
                <img src="{{ $item['thumbnail'] ?? $galleryItems[0]['url'] }}" alt="" class="w-full h-full object-cover opacity-80" loading="lazy" />
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-5 h-5 text-[7px] md:w-6 md:h-6 md:text-[8px] bg-red-600 rounded-full flex items-center justify-center shadow-lg border-2 border-white/40">
                        <i class="fa-solid fa-play text-white ml-0.5"></i>
                    </div>
                </div>
            </button>
            @endif
        @endforeach
    </div>
        <button type="button" @click="next()" aria-label="Siguiente" class="flex-shrink-0 w-7 h-7 md:w-9 md:h-9 flex items-center justify-center rounded-full border border-gray-200 text-gray-500 hover:border-gray-400 hover:text-gray-800 transition-colors">
            <i class="fa-solid fa-chevron-right text-xs"></i>
        </button>
    </div>
    @endif
</div>
