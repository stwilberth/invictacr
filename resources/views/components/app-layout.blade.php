<!doctype html>
<html lang="es" class="transition-colors duration-300">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width" />
    <meta name="facebook-domain-verification" content="2rf7uymq80aja4vxcb6l7hmugbyouf" />
    <link rel="icon" type="image/x-icon" href="/favicon.ico" />
    <title>{{ $title ?? '' }} | Invicta Costa Rica</title>
    <meta name="description" content="{{ $description ?? 'Invicta Costa Rica - Relojes de alta calidad con los mejores precios. Pago contra entrega en GAM y envío gratis a todo el país.' }}" />
    <link rel="canonical" href="{{ url()->current() }}" />
    <link rel="sitemap" href="/sitemap.xml" />

    <meta property="og:title" content="{{ $title ?? '' }} | Invicta Costa Rica" />
    <meta property="og:description" content="{{ $description ?? 'Invicta Costa Rica - Relojes de alta calidad con los mejores precios. Pago contra entrega en GAM y envío gratis a todo el país.' }}" />
    <meta property="og:type" content="{{ $ogType ?? 'website' }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:site_name" content="Invicta Costa Rica" />
    <meta property="og:image" content="{{ $ogImage ?? asset('logo.webp') }}" />
    <meta property="og:locale" content="es_CR" />

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ $title ?? '' }} | Invicta Costa Rica" />
    <meta name="twitter:description" content="{{ $description ?? 'Invicta Costa Rica - Relojes de alta calidad con los mejores precios. Pago contra entrega en GAM y envío gratis a todo el país.' }}" />
    <meta name="twitter:image" content="{{ $ogImage ?? asset('logo.webp') }}" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" media="print" onload="this.media='all'" />
    <noscript>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    </noscript>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin />
    <link rel="preconnect" href="https://www.googletagmanager.com" crossorigin />
    <link rel="preconnect" href="https://connect.facebook.net" crossorigin />

    <script>
        (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-MFKHNJ9V');
    </script>

    <script>
        !(function (f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function () {
                n.callMethod
                    ? n.callMethod.apply(n, arguments)
                    : n.queue.push(arguments);
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = "2.0";
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s);
        })(window, document, "script", "https://connect.facebook.net/en_US/fbevents.js");
        fbq("init", "1666700714574473");
        fbq("track", "PageView");
    </script>
    <noscript>
        <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=1666700714574473&ev=PageView&noscript=1" />
    </noscript>

    <script>
        document.addEventListener("click", function(e) {
            var link = e.target.closest('a[href*="wa.me"], a[href*="api.whatsapp.com"]');
            if (!link) return;
            var productModel = typeof pixelModel !== "undefined" ? pixelModel : "";
            var productTitle = typeof pixelTitle !== "undefined" ? pixelTitle : "";
            var productPrice = typeof pixelPrice !== "undefined" ? pixelPrice : 0;
            if (typeof fbq !== "undefined") {
                fbq("track", "Contact", {
                    content_ids: productModel ? [productModel] : [],
                    content_name: productTitle || document.title,
                    content_type: "product",
                    value: productPrice,
                    currency: "CRC",
                    event_url: window.location.href,
                });
            }
        });
    </script>

    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@type": "Organization",
            "name": "Invicta Costa Rica",
            "url": "{{ config('app.url') }}",
            "logo": "{{ asset('logo.webp') }}",
            "contactPoint": {
                "@type": "ContactPoint",
                "telephone": "+506-8671-1422",
                "contactType": "customer service",
                "availableLanguage": "Spanish"
            },
            "sameAs": [
                "https://www.facebook.com/invictacostarica",
                "https://www.instagram.com/invictacostarica"
            ]
        }
    </script>

    <style>
        .swiper-slide {
            width: auto !important;
            min-width: 280px !important;
        }
    </style>

    <script>
        const theme = (typeof localStorage !== "undefined" && localStorage.getItem("theme")) || "light";
        if (theme === "dark") {
            document.documentElement.classList.add("dark");
        } else {
            document.documentElement.classList.remove("dark");
        }
        window.localStorage.setItem("theme", theme);
    </script>

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{ $head ?? '' }}
</head>
<body class="bg-white text-gray-900 dark:bg-[#121212] dark:text-gray-100">
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MFKHNJ9V" height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>

    @unless($hideNav ?? false)
    <x-navbar :q="$q ?? null" />
    @endunless

    <main class="min-h-screen">
        {{ $slot }}
    </main>

    <x-footer />
    @unless($hideWhatsApp ?? false)
        <x-whatsapp-button />
    @endunless

    <!-- Image Modal -->
    <div id="imageModal" class="modal-overlay fixed inset-0 z-[100] hidden items-center justify-center bg-black/85 p-0">
        <div class="relative w-full h-full flex items-center justify-center">
            <button type="button" onclick="closeImageModal()" aria-label="Cerrar" class="absolute top-4 right-4 z-40 flex items-center justify-center w-12 h-12 bg-white/90 hover:bg-white text-gray-800 rounded-full text-lg shadow-2xl border-2 border-gray-300 hover:border-gray-500 transition-all duration-200">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <button type="button" id="modalPrevBtn" onclick="prevImage()" aria-label="Anterior" class="absolute left-4 top-1/2 -translate-y-1/2 z-40 flex items-center justify-center w-14 h-14 bg-white/80 hover:bg-white/95 text-gray-800 rounded-full shadow-2xl border-2 border-white/50 hover:border-white transition-all duration-200">
                <i class="fa-solid fa-chevron-left text-xl"></i>
            </button>
            <button type="button" id="modalNextBtn" onclick="nextImage()" aria-label="Siguiente" class="absolute right-4 top-1/2 -translate-y-1/2 z-40 flex items-center justify-center w-14 h-14 bg-white/80 hover:bg-white/95 text-gray-800 rounded-full shadow-2xl border-2 border-white/50 hover:border-white transition-all duration-200">
                <i class="fa-solid fa-chevron-right text-xl"></i>
            </button>
            <div class="flex items-center justify-center p-8" style="max-width: 94vw; max-height: 94vh;">
                <img id="imageModalImg" src="" alt="" class="max-w-full max-h-full w-auto h-auto object-contain rounded-2xl shadow-2xl" style="max-height: 88vh;" />
            </div>
        </div>
    </div>

    <!-- Vimeo Video Modal -->
    <div id="vimeoModal" class="modal-overlay fixed inset-0 z-[100] hidden items-center justify-center bg-black/90 backdrop-blur-sm p-2 sm:p-4">
        <div class="relative modal-content flex items-center justify-center" id="vimeoContainer" style="width: 90vw; height: 80vh;">
            <button type="button" onclick="closeVimeoModal()" aria-label="Cerrar" class="absolute -top-3 right-2 sm:right-0 z-20 flex items-center justify-center w-10 h-10 bg-white/90 hover:bg-white text-gray-900 rounded-full text-base shadow-2xl border-2 border-gray-300 hover:border-gray-600 transition-all duration-200">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div class="w-full h-full bg-black rounded-2xl overflow-hidden shadow-2xl shadow-black/50 flex items-center justify-center">
                <iframe id="vimeoFrame" src="" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen class="w-full h-full" frameborder="0"></iframe>
            </div>
        </div>
    </div>

    <style>
    .modal-overlay {
        opacity: 0;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }
    .modal-overlay.active {
        opacity: 1;
    }
    .modal-overlay .modal-content {
        transform: scale(0.92);
        transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .modal-overlay.active .modal-content {
        transform: scale(1);
    }
    </style>

    @push('scripts')
    <script>
    function openModal(id) {
        var el = document.getElementById(id);
        if (!el) return;
        el.classList.remove('hidden');
        el.classList.add('flex');
        document.body.style.overflow = 'hidden';
        requestAnimationFrame(function() {
            el.classList.add('active');
        });
    }
    function closeModal(id) {
        var el = document.getElementById(id);
        if (!el) return;
        el.classList.remove('active');
        document.body.style.overflow = '';
        setTimeout(function() {
            el.classList.add('hidden');
            el.classList.remove('flex');
        }, 300);
    }

    var imageGallery = [];
    var currentImageIndex = 0;

    function openImageModal(src, alt) {
        var img = document.getElementById('imageModalImg');
        if (!img) return;
        var thumbs = document.querySelectorAll('[data-gallery-img]');
        imageGallery = [];
        thumbs.forEach(function(el) { imageGallery.push(el.getAttribute('data-gallery-img')); });
        if (imageGallery.length === 0) imageGallery = [src];
        currentImageIndex = imageGallery.indexOf(src);
        if (currentImageIndex === -1) currentImageIndex = 0;
        img.src = src;
        img.alt = alt || '';
        updateNavButtons();
        openModal('imageModal');
    }
    function closeImageModal() {
        var img = document.getElementById('imageModalImg');
        if (img) img.src = '';
        imageGallery = [];
        closeModal('imageModal');
    }
    function prevImage() {
        if (imageGallery.length < 2) return;
        currentImageIndex = (currentImageIndex - 1 + imageGallery.length) % imageGallery.length;
        var img = document.getElementById('imageModalImg');
        img.src = imageGallery[currentImageIndex];
        updateNavButtons();
    }
    function nextImage() {
        if (imageGallery.length < 2) return;
        currentImageIndex = (currentImageIndex + 1) % imageGallery.length;
        var img = document.getElementById('imageModalImg');
        img.src = imageGallery[currentImageIndex];
        updateNavButtons();
    }
    function updateNavButtons() {
        var prev = document.getElementById('modalPrevBtn');
        var next = document.getElementById('modalNextBtn');
        if (!prev || !next) return;
        var show = imageGallery.length > 1;
        prev.style.display = show ? '' : 'none';
        next.style.display = show ? '' : 'none';
    }

    function openVimeoModal(input) {
        var frame = document.getElementById('vimeoFrame');
        var container = document.getElementById('vimeoContainer');
        if (!frame || !container) return;
        var id = getVimeoId(input);
        if (!id) return;
        frame.src = 'https://player.vimeo.com/video/' + id + '?autoplay=1&title=0&byline=0&portrait=0';
        openModal('vimeoModal');
        fetch('https://vimeo.com/api/oembed.json?url=https://vimeo.com/' + id)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                var w = data.width || 640;
                var h = data.height || 360;
                var vw = window.innerWidth;
                var vh = window.innerHeight;
                var maxW = vw * 0.92;
                var maxH = vh * 0.88;
                var ratio = w / h;
                var cw = maxW;
                var ch = cw / ratio;
                if (ch > maxH) {
                    ch = maxH;
                    cw = ch * ratio;
                }
                container.style.width = Math.round(cw) + 'px';
                container.style.height = Math.round(ch) + 'px';
            })
            .catch(function() {});
    }
    function closeVimeoModal() {
        var frame = document.getElementById('vimeoFrame');
        if (frame) frame.src = '';
        closeModal('vimeoModal');
    }

    var modalIds = ['imageModal', 'vimeoModal'];
    modalIds.forEach(function(id) {
        var el = document.getElementById(id);
        if (el) {
            el.addEventListener('click', function(e) { if (e.target === el) closeModal(id); });
        }
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
            closeVimeoModal();
        }
        if (e.key === 'ArrowLeft') {
            var modal = document.getElementById('imageModal');
            if (modal && !modal.classList.contains('hidden')) prevImage();
        }
        if (e.key === 'ArrowRight') {
            var modal = document.getElementById('imageModal');
            if (modal && !modal.classList.contains('hidden')) nextImage();
        }
    });

    function getVimeoId(input) {
        if (!input) return null;
        input = input.trim();
        var match = input.match(/(?:vimeo\.com\/(?:video\/)?|player\.vimeo\.com\/video\/)?(\d+)/i);
        return match ? match[1] : null;
    }
    </script>
    @endpush

    @stack('scripts')
</body>
</html>
