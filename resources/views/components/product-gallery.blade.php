@props(['galleryItems', 'title', 'variant' => 'desktop', 'extraSlides' => 0, 'extraThumbIcons' => []])

@php
    $isMobile = $variant === 'mobile';
    $hasExtra = (int) $extraSlides > 0;
    $totalSlides = count($galleryItems) + (int) $extraSlides;
    $streamSubdomain = config('services.cloudflare.stream_customer_subdomain', 'customer-8ybt5aiee4vaophw');
    $navLeft = $isMobile
        ? 'absolute left-1 top-1/2 -translate-y-1/2 w-7 h-7 bg-white/80 dark:bg-gray-900/80 text-gray-600 dark:text-gray-300 rounded-full shadow flex items-center justify-center transition-all opacity-60 z-20'
        : 'absolute left-4 top-1/2 -translate-y-1/2 w-11 h-11 bg-[#101828] hover:bg-[#1d2939] text-white rounded-full shadow-lg flex items-center justify-center transition-all duration-300 z-20';
    $navRight = str_replace('left-1', 'right-1', str_replace('left-4', 'right-4', $navLeft));
    $navChevron = $isMobile ? 'text-[10px]' : 'text-base';
    $thumbSize = $isMobile ? 'w-14 h-14' : 'w-[84px] h-[84px]';
    $playSize = $isMobile ? 'w-10 h-10' : 'w-16 h-16';
    $playIcon = $isMobile ? 'text-lg' : 'text-2xl';
    $playInner = $isMobile ? 'w-5 h-5 text-[7px]' : 'w-6 h-6 text-[8px]';
    $zoomBtn = $isMobile
        ? 'absolute bottom-3 right-3 w-7 h-7 bg-white/80 dark:bg-gray-900/80 text-gray-600 dark:text-gray-300 rounded-full shadow flex items-center justify-center opacity-60 z-20'
        : 'absolute bottom-4 right-4 w-9 h-9 bg-white/95 dark:bg-gray-900/95 border border-gray-200 dark:border-gray-700/80 text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-lg shadow-sm flex items-center justify-center transition-all duration-300 opacity-0 group-hover/image:opacity-100 scale-95 group-hover/image:scale-100 z-30';
    $videoItem = collect($galleryItems)->first(fn($i) => ($i['type'] ?? 'image') !== 'image');
    $videoUid = $videoItem['videoUid'] ?? $videoItem['video_uid'] ?? null;
@endphp

<div x-data='{
    galleryItems: @json($galleryItems),
    currentIndex: 0,
    totalSlides: {{ $totalSlides }},
    playingVideo: null,
    touchStartX: null,
    modalTitle: @json($title),
    hasVideo() {
        return this.galleryItems.some((i) => i.type !== "image");
    },
    videoIndex() {
        const idx = this.galleryItems.findIndex((i) => i.type !== "image");
        return idx === -1 ? 0 : idx;
    },
    openModalVideo() {        const v = this.galleryItems.find((i) => i.type !== "image");
        const uid = v ? (v.videoUid || v.video_uid || null) : null;
        if (!uid || typeof openImageModal !== "function") return;
        const imgItem = this.galleryItems.find((i) => i.type === "image");
        const src = imgItem ? (imgItem.zoomUrl || imgItem.url || "") : "";
        openImageModal(src, this.modalTitle, uid, true);
    },
    goTo(i) {
        this.playingVideo = null;
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
    playVideo(uid) {
        this.playingVideo = uid;
    },
    prev() {
        this.playingVideo = null;
        this.currentIndex = (this.currentIndex - 1 + this.totalSlides) % this.totalSlides;
    },
    next() {
        this.playingVideo = null;
        this.currentIndex = (this.currentIndex + 1) % this.totalSlides;
    }
}'>
    <div class="relative overflow-hidden group/image w-full {{ $isMobile ? '' : 'aspect-[4/3] rounded-xl bg-[#F1F5FA]' }}" @if($isMobile) style="height: 260px;" @endif
        @touchstart="onTouchStart($event)" @touchend="onTouchEnd($event)">
        <div class="absolute inset-0 flex" :style="`transform: translateX(-${currentIndex * 100}%); transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);`">
            @foreach($galleryItems as $idx => $item)
            <div class="relative w-full h-full flex-shrink-0 flex items-center justify-center">
                @if($item['type'] === 'image')
                    @if($isMobile)
                    <div class="absolute inset-0 flex items-center justify-center">
                        <img src="{{ $item['url'] }}" alt="{{ $title }} - {{ $idx + 1 }}" class="w-full h-full object-contain" @if($idx === 0) fetchpriority="high" @else loading="lazy" @endif />
                    </div>
                    @else
                    <div class="absolute inset-0 flex items-center justify-center cursor-zoom-in" @click="openImageModal('{{ $item['zoomUrl'] }}', '{{ $title }}', '{{ $videoUid }}')">
                        <img src="{{ $item['url'] }}" alt="{{ $title }} - {{ $idx + 1 }}" class="w-full h-full object-contain transition-transform duration-500 hover:scale-[1.02]" @if($idx === 0) fetchpriority="high" @else loading="lazy" @endif />
                    </div>
                    @endif
                @else
                    @if($isMobile)
                    <div class="absolute inset-0 flex items-center justify-center">
                        <template x-if="playingVideo !== '{{ $item['videoUid'] }}'">
                            <button type="button" @click="playVideo('{{ $item['videoUid'] }}')" class="absolute inset-0 flex items-center justify-center cursor-pointer">
                                <img src="{{ $item['thumbnail'] ?? $galleryItems[0]['url'] }}" alt="Video del reloj" class="w-full h-full object-contain" loading="lazy" />
                                <div class="absolute inset-0 flex flex-col items-center justify-center gap-1.5">
                                    <div class="{{ $playSize }} bg-red-600 rounded-full flex items-center justify-center shadow-2xl border-4 border-white/30 hover:border-white/60 transition-all duration-300 hover:scale-110">
                                        <i class="fa-solid fa-play text-white {{ $playIcon }} ml-1"></i>
                                    </div>
                                    <span class="bg-black/60 backdrop-blur-sm text-white text-[9px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full">
                                        <i class="fa-solid fa-play text-[8px] mr-1"></i> Ver video
                                    </span>
                                </div>
                            </button>
                        </template>
                        <template x-if="playingVideo === '{{ $item['videoUid'] }}'">
                            <div class="absolute inset-0 flex items-center justify-center bg-black">
                                <iframe src="https://{{ $streamSubdomain }}.cloudflarestream.com/{{ $item['videoUid'] }}/iframe?autoplay=1" class="w-full h-full" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen frameborder="0"></iframe>
                            </div>
                        </template>
                    </div>
                    @else
                    <div class="absolute inset-0 flex items-center justify-center cursor-pointer" @click="openModalVideo()">
                        <img src="{{ $item['thumbnail'] ?? $galleryItems[0]['url'] }}" alt="Video del reloj" class="w-full h-full object-cover" loading="lazy" />
                        <div class="absolute inset-0 flex flex-col items-center justify-center gap-1.5">
                            <div class="{{ $playSize }} bg-red-600 rounded-full flex items-center justify-center shadow-2xl border-4 border-white/30 hover:border-white/60 transition-all duration-300 hover:scale-110">
                                <i class="fa-solid fa-play text-white {{ $playIcon }} ml-1"></i>
                            </div>
                            <span class="bg-black/60 backdrop-blur-sm text-white text-[9px] font-black uppercase tracking-wider px-2.5 py-1 rounded-full">
                                <i class="fa-solid fa-play text-[8px] mr-1"></i> Ver video
                            </span>
                        </div>
                    </div>
                    @endif
                @endif
            </div>
            @endforeach

            @if($hasExtra)
                {{ $slot }}
            @endif
        </div>

        {{-- Navigation buttons --}}
        <button type="button" @click="prev()" x-show="totalSlides > 1" class="{{ $navLeft }}" aria-label="Anterior">
            <i class="fa-solid fa-chevron-left {{ $navChevron }}"></i>
        </button>
        <button type="button" @click="next()" x-show="totalSlides > 1" class="{{ $navRight }}" aria-label="Siguiente">
            <i class="fa-solid fa-chevron-right {{ $navChevron }}"></i>
        </button>

        {{-- Badge único VER VIDEO REAL: solo en desktop, solo sobre fotos, nunca duplicado --}}
        @if(!$isMobile)
        <button type="button" x-show="hasVideo() && galleryItems[currentIndex]?.type === 'image'" @click="openModalVideo()" class="absolute bottom-4 left-4 z-30 flex items-center gap-2 bg-[#101828]/90 hover:bg-[#101828] text-white text-xs font-extrabold px-4 py-2 rounded-full shadow-md transition-colors">
            <i class="fa-solid fa-play text-xs"></i> VER VIDEO REAL
        </button>
        @endif

        {{-- Zoom button (only desktop, only on images) --}}
        @if(!$isMobile)
        <button type="button" x-show="galleryItems[currentIndex]?.type === 'image'" @click="event.preventDefault(); openImageModal(galleryItems[currentIndex].zoomUrl, '{{ $title }}', '{{ $videoUid }}')" class="{{ $zoomBtn }} cursor-pointer">
            <i class="fa-solid fa-expand text-sm"></i>
        </button>
        @endif
    </div>

    {{-- Thumbnail gallery --}}
    @if($totalSlides > 1)
    <div class="flex items-center gap-2 {{ $isMobile ? 'px-1 pb-2 mt-2' : 'mt-3' }}">
        @if(!$isMobile)
        <button type="button" @click="prev()" aria-label="Anterior" class="flex-shrink-0 w-9 h-9 flex items-center justify-center rounded-full border border-gray-200 text-gray-500 hover:border-gray-400 hover:text-gray-800 transition-colors">
            <i class="fa-solid fa-chevron-left text-xs"></i>
        </button>
        @endif
    <div class="flex gap-2.5 {{ $isMobile ? '' : 'flex-1 justify-center' }} overflow-x-auto scrollbar-hide">
        @foreach($galleryItems as $i => $item)
            @if($item['type'] === 'image')
            <button type="button" @click="goTo({{ $i }})" data-gallery-img="{{ $item['zoomUrl'] }}" class="{{ $thumbSize }} flex-shrink-0 rounded-[10px] border-2 overflow-hidden bg-gray-50 dark:bg-gray-900 transition-all gallery-thumb"
                :class="currentIndex === {{ $i }} ? 'border-[#0A7CFF]' : 'border-transparent hover:border-gray-300 dark:hover:border-gray-600'">
                <img src="{{ $item['url'] }}" alt="" class="w-full h-full object-contain" loading="lazy" onerror="this.closest('.gallery-thumb').style.display='none'" />
            </button>
            @else
            <button type="button" @click="goTo({{ $i }})" class="{{ $thumbSize }} flex-shrink-0 rounded-[10px] border-2 overflow-hidden bg-gray-900 transition-all gallery-thumb relative"
                :class="currentIndex === {{ $i }} ? 'border-[#0A7CFF]' : 'border-transparent hover:border-gray-300 dark:hover:border-gray-600'">
                <img src="{{ $item['thumbnail'] ?? $galleryItems[0]['url'] }}" alt="" class="w-full h-full object-cover opacity-80" loading="lazy" />
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="{{ $playInner }} bg-red-600 rounded-full flex items-center justify-center shadow-lg border-2 border-white/40">
                        <i class="fa-solid fa-play text-white ml-0.5"></i>
                    </div>
                </div>
            </button>
            @endif
        @endforeach

        @if($hasExtra)
            @for($x = 0; $x < (int) $extraSlides; $x++)
            <button type="button" @click="goTo({{ count($galleryItems) + $x }})" class="{{ $thumbSize }} flex-shrink-0 rounded-lg border-2 overflow-hidden bg-gray-50 dark:bg-gray-900 transition-all gallery-thumb flex items-center justify-center"
                :class="currentIndex === {{ count($galleryItems) + $x }} ? 'border-[#0A7CFF]' : 'border-transparent hover:border-gray-300 dark:hover:border-gray-600'">
                <i class="fa-solid {{ $extraThumbIcons[$x] ?? 'fa-circle-info' }} text-gray-400 dark:text-gray-500 text-lg"></i>
            </button>
            @endfor
        @endif
    </div>
        @if(!$isMobile)
        <button type="button" @click="next()" aria-label="Siguiente" class="flex-shrink-0 w-9 h-9 flex items-center justify-center rounded-full border border-gray-200 text-gray-500 hover:border-gray-400 hover:text-gray-800 transition-colors">
            <i class="fa-solid fa-chevron-right text-xs"></i>
        </button>
        @endif
    </div>
    @endif
</div>
