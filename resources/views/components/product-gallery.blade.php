@props(['galleryItems', 'title', 'refCode' => null])

@php
    $totalSlides = count($galleryItems);
    $navBase = 'absolute top-1/2 -translate-y-1/2 w-7 h-7 md:w-11 md:h-11 bg-[#1E293B] hover:bg-[#334155] text-white rounded-full shadow-lg flex items-center justify-center transition-all duration-300 z-20';
    $navLeft = 'left-2 md:left-4 ' . $navBase;
    $navRight = 'right-2 md:right-4 ' . $navBase;
    $firstImageUrl = collect($galleryItems)->firstWhere('type', 'image')['url'] ?? null;
    $streamSubdomain = config('services.cloudflare.stream_customer_subdomain', 'customer-8ybt5aiee4vaophw');
@endphp

<div x-data='{
    galleryItems: @json($galleryItems),
    currentIndex: 0,
    totalSlides: {{ $totalSlides }},
    touchStartX: null,
    videoPlaying: false,
    goTo(i) {
        this.currentIndex = i;
        this.videoPlaying = false;
        this.revealThumb();
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
        this.videoPlaying = false;
        this.revealThumb();
    },
    next() {
        this.currentIndex = (this.currentIndex + 1) % this.totalSlides;
        this.videoPlaying = false;
        this.revealThumb();
    },
    stripScroll(dir) {
        const s = this.$refs.thumbStrip;
        if (!s) return;
        s.scrollBy({ left: dir * Math.max(s.clientWidth * 0.7, 140), behavior: "smooth" });
    },
    revealThumb() {
        this.$nextTick(() => {
            const s = this.$refs.thumbStrip;
            if (!s || !s.children[this.currentIndex]) return;
            s.children[this.currentIndex].scrollIntoView({ behavior: "smooth", inline: "center", block: "nearest" });
        });
    }
}'>
        <div class="relative overflow-hidden rounded-xl bg-white dark:bg-gray-900" @touchstart="onTouchStart($event)" @touchend="onTouchEnd($event)">
            @foreach($galleryItems as $idx => $item)
            <div x-show="currentIndex === {{ $idx }}" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="w-full aspect-video flex items-center justify-center">
                @if($item['type'] === 'image')
                    <img src="{{ $item['url'] }}" alt="{{ $title }} - {{ $idx + 1 }}" class="w-full h-full object-contain" @if($idx === 0) fetchpriority="high" @else loading="lazy" @endif />
                @else
                    <div class="w-full">
                        <template x-if="!videoPlaying">
                            <button type="button" @click="videoPlaying = true" class="relative block w-full aspect-video bg-black cursor-pointer" aria-label="Reproducir video">
                                <img src="{{ $item['cover'] ?? $item['thumbnail'] ?? $firstImageUrl }}" alt="Video del reloj" class="absolute inset-0 w-full h-full object-cover" loading="lazy" />
                                <span class="absolute inset-0 flex flex-col items-center justify-center gap-1.5">
                                    <span class="w-12 h-12 md:w-16 md:h-16 bg-red-600 rounded-full flex items-center justify-center shadow-2xl border-4 border-white/30 hover:border-white/60 transition-all duration-300 hover:scale-110">
                                        <i class="fa-solid fa-play text-white text-lg md:text-2xl ml-1"></i>
                                    </span>
                                    <span class="bg-black/60 backdrop-blur-sm text-white text-[9px] font-black uppercase tracking-wider px-2.5 py-1 rounded-full">
                                        <i class="fa-solid fa-play text-[8px] mr-1"></i> Ver video
                                    </span>
                                </span>
                            </button>
                        </template>
                        <template x-if="videoPlaying">
                            <div class="w-full aspect-video bg-black">
                                <iframe src="https://{{ $streamSubdomain }}.cloudflarestream.com/{{ $item['videoUid'] ?? $item['video_uid'] }}/iframe?autoplay=true" title="Video del reloj {{ $title }}" class="w-full h-full border-0" allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;" allowfullscreen="true"></iframe>
                            </div>
                        </template>
                    </div>
                @endif
            </div>
            @endforeach

        {{-- Navigation buttons --}}
        <button type="button" @click="prev()" x-show="totalSlides > 1" class="{{ $navLeft }}" aria-label="Anterior">
            <i class="fa-solid fa-chevron-left text-xs md:text-base"></i>
        </button>
        <button type="button" @click="next()" x-show="totalSlides > 1" class="{{ $navRight }}" aria-label="Siguiente">
            <i class="fa-solid fa-chevron-right text-xs md:text-base"></i>
        </button>

        {{-- Código ref del visitante: visible para que el cliente lo muestre por captura --}}
        @if($refCode)
        <div class="absolute bottom-2 right-2 z-30 inline-flex items-center bg-black/40 backdrop-blur-sm text-white text-[11px] font-black uppercase tracking-[0.2em] px-2.5 py-1 rounded-full shadow-lg md:text-xl md:px-5 md:py-2.5">
            {{ $refCode }}
        </div>
        @endif
    </div>

    {{-- Thumbnail gallery --}}
    @if($totalSlides > 1)
    <div class="flex items-center gap-2 mt-3">
        <button type="button" @click="stripScroll(-1)" aria-label="Anterior" class="flex-shrink-0 w-7 h-7 md:w-9 md:h-9 flex items-center justify-center rounded-full border border-gray-200 text-gray-500 hover:border-gray-400 hover:text-gray-800 transition-colors">
            <i class="fa-solid fa-chevron-left text-xs"></i>
        </button>
    <div x-ref="thumbStrip" class="flex gap-2 md:gap-2.5 flex-1 justify-start overflow-x-auto scrollbar-hide" style="justify-content:safe center">
        @foreach($galleryItems as $i => $item)
            @if($item['type'] === 'image')
            <button type="button" @click="goTo({{ $i }})" class="w-20 h-20 md:w-24 md:h-24 flex-shrink-0 rounded-[10px] border-2 overflow-hidden bg-gray-50 dark:bg-gray-900 transition-all gallery-thumb"
                :class="currentIndex === {{ $i }} ? 'border-[#0A7CFF]' : 'border-transparent hover:border-gray-300 dark:hover:border-gray-600'">
                <img src="{{ $item['url'] }}" alt="" class="w-full h-full object-cover" loading="lazy" />
            </button>
            @else
            <button type="button" @click="goTo({{ $i }})" class="w-20 h-20 md:w-24 md:h-24 flex-shrink-0 rounded-[10px] border-2 overflow-hidden bg-gray-900 transition-all gallery-thumb relative"
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
        <button type="button" @click="stripScroll(1)" aria-label="Siguiente" class="flex-shrink-0 w-7 h-7 md:w-9 md:h-9 flex items-center justify-center rounded-full border border-gray-200 text-gray-500 hover:border-gray-400 hover:text-gray-800 transition-colors">
            <i class="fa-solid fa-chevron-right text-xs"></i>
        </button>
    </div>
    @endif
</div>
