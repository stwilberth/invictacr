<!doctype html>
<html lang="es" class="transition-colors duration-300" style="overflow-x: clip;">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width" />
    <meta name="facebook-domain-verification" content="2rf7uymq80aja4vxcb6l7hmugbyouf" />
    <link rel="icon" type="image/x-icon" href="/favicon.ico" />
    <link rel="icon" type="image/png" sizes="32x32" href="/logo.webp" />
    <link rel="apple-touch-icon" href="/logo.png" />
    <title>{{ $title ?? '' }}{{ ($titleSuffix ?? true) ? ' | Invicta Costa Rica' : '' }}</title>
    <meta name="description" content="{{ $description ?? 'Invicta Costa Rica - Relojes de alta calidad con los mejores precios. Pago contra entrega en GAM y envío gratis a todo el país.' }}" />
    <link rel="canonical" href="{{ url()->current() }}" />
    <link rel="sitemap" href="/sitemap.xml" />

    <meta property="og:title" content="{{ $title ?? '' }}{{ ($titleSuffix ?? true) ? ' | Invicta Costa Rica' : '' }}" />
    <meta property="og:description" content="{{ $description ?? 'Invicta Costa Rica - Relojes de alta calidad con los mejores precios. Pago contra entrega en GAM y envío gratis a todo el país.' }}" />
    <meta property="og:type" content="{{ $ogType ?? 'website' }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:site_name" content="Invicta Costa Rica" />
    <meta property="og:image" content="{{ $ogImage ?? asset('logo.webp') }}" />
    <meta property="og:locale" content="es_CR" />

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ $title ?? '' }}{{ ($titleSuffix ?? true) ? ' | Invicta Costa Rica' : '' }}" />
    <meta name="twitter:description" content="{{ $description ?? 'Invicta Costa Rica - Relojes de alta calidad con los mejores precios. Pago contra entrega en GAM y envío gratis a todo el país.' }}" />
    <meta name="twitter:image" content="{{ $ogImage ?? asset('logo.webp') }}" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" media="print" onload="this.media='all'" />
    <noscript>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    </noscript>

    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/webfonts/fa-solid-900.woff2" as="font" type="font/woff2" crossorigin />
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/webfonts/fa-brands-400.woff2" as="font" type="font/woff2" crossorigin />

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin />
    <link rel="preconnect" href="https://cdn.invictacostarica.com" crossorigin />
    <link rel="preconnect" href="https://www.googletagmanager.com" crossorigin />
    <link rel="preconnect" href="https://connect.facebook.net" crossorigin />

    <script>
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({'gtm.start': new Date().getTime(), event: 'gtm.js'});

        !(function (f, b, e, v, n) {
            if (f.fbq) return;
            n = f.fbq = function () {
                n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments);
            };
            if (!f._fbq) f._fbq = n;
            n.push = n; n.loaded = !0; n.version = "2.0"; n.queue = [];
        })(window, document, "script");
        fbq("init", "1666700714574473");
        fbq("track", "PageView");

        (function () {
            var loaded = false;
            function loadTrackers() {
                if (loaded) return;
                loaded = true;
                var g = document.createElement("script");
                g.async = true;
                g.src = "https://www.googletagmanager.com/gtm.js?id=GTM-MFKHNJ9V";
                document.head.appendChild(g);
                var fb = document.createElement("script");
                fb.async = true;
                fb.src = "https://connect.facebook.net/en_US/fbevents.js";
                document.head.appendChild(fb);
                ["scroll", "click", "touchstart", "keydown", "mousemove"].forEach(function (ev) {
                    window.removeEventListener(ev, loadTrackers, { passive: true });
                });
            }
            ["scroll", "click", "touchstart", "keydown", "mousemove"].forEach(function (ev) {
                window.addEventListener(ev, loadTrackers, { passive: true, once: true });
            });
            if ("requestIdleCallback" in window) {
                requestIdleCallback(loadTrackers, { timeout: 4000 });
            } else {
                setTimeout(loadTrackers, 4000);
            }
        })();
    </script>
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MFKHNJ9V" height="0" width="0" style="display:none;visibility:hidden"></iframe>
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

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {!! $head ?? '' !!}
    @stack('json-ld')
</head>
<body class="bg-white text-gray-900 dark:bg-[#121212] dark:text-gray-100" style="overflow-x: clip;">
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
    <x-cookie-banner />

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

    @push('scripts')
    <script>
    function addToCart(productId, btn) {
        if (btn && btn.disabled) return;
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-[9px]"></i>';
        }
        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ product_id: productId, quantity: 1 }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                if (window.invictaTrack) {
                    window.invictaTrack('add_to_cart', { product_id: productId });
                }
                if (btn) {
                    btn.innerHTML = '<i class="fa-solid fa-check text-[9px]"></i> Agregado';
                    btn.classList.remove('bg-[#00C4FF]', 'hover:bg-[#00a3d6]');
                    btn.classList.add('bg-green-500');
                    setTimeout(() => {
                        btn.innerHTML = '<i class="fa-solid fa-cart-shopping text-[9px]"></i> Ver Carrito';
                        btn.classList.add('bg-gray-800', 'hover:bg-gray-700');
                        btn.classList.remove('bg-green-500');
                        btn.disabled = false;
                        btn.onclick = function(e) {
                            e.preventDefault();
                            window.location.href = '/carrito';
                        };
                    }, 1500);
                }
                document.querySelectorAll('[data-cart-count]').forEach(el => {
                    el.textContent = data.cart_count;
                    el.style.display = data.cart_count > 0 ? '' : 'none';
                });
                document.querySelectorAll('.cart-badge-desktop, .cart-badge-mobile').forEach(el => {
                    if (data.cart_count > 0) {
                        el.textContent = data.cart_count > 9 ? '9+' : data.cart_count;
                        el.style.display = '';
                    } else {
                        el.style.display = 'none';
                    }
                });
            } else {
                if (btn) {
                    btn.innerHTML = '<i class="fa-solid fa-cart-plus text-[9px]"></i> Agregar';
                    btn.disabled = false;
                }
                alert(data.message || 'Error al agregar');
            }
        })
        .catch(() => {
            if (btn) {
                btn.innerHTML = '<i class="fa-solid fa-cart-plus text-[9px]"></i> Agregar';
                btn.disabled = false;
            }
        });
    }
    </script>
    @endpush

    <script>
    (function () {
        var productId = typeof window.invictaProductId !== 'undefined' ? window.invictaProductId : null;
        var eventId = null;
        var secondsAccum = 0;
        var lastTick = Date.now();
        var started = false;

        function consentAccepted() {
            return window.invictaConsent && window.invictaConsent.accepted();
        }

        function post(path, data, useBeacon) {
            var body = JSON.stringify(data);
            if (useBeacon && navigator.sendBeacon) {
                navigator.sendBeacon(path, new Blob([body], { type: 'application/json' }));
                return;
            }
            fetch(path, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: body,
                credentials: 'same-origin',
                keepalive: !!useBeacon
            }).then(function (r) { return r.json(); })
              .then(function (res) {
                  if (res && res.event_id) eventId = res.event_id;
              })
              .catch(function () {});
        }

        function tick() {
            var now = Date.now();
            if (document.visibilityState === 'visible') {
                secondsAccum += Math.round((now - lastTick) / 1000);
            }
            lastTick = now;
        }

        function flushHeartbeat(useBeacon) {
            tick();
            if (secondsAccum < 1 || !eventId) return;
            post('/track/heartbeat', { event_id: eventId, seconds: secondsAccum }, useBeacon);
            secondsAccum = 0;
        }

        function start() {
            if (started) return;
            started = true;

            var params = new URLSearchParams(window.location.search);

            post('/track/event', {
                type: productId ? 'product_view' : 'page_view',
                url: window.location.href,
                title: document.title,
                product_id: productId
            });

            if (params.get('q')) {
                post('/track/event', {
                    type: 'search',
                    url: window.location.href,
                    title: document.title,
                    query: params.get('q')
                });
            }

            setInterval(function () { flushHeartbeat(false); }, 15000);

            document.addEventListener('visibilitychange', function () {
                if (document.visibilityState === 'hidden') {
                    flushHeartbeat(true);
                } else {
                    lastTick = Date.now();
                }
            });

            window.addEventListener('pagehide', function () {
                flushHeartbeat(true);
            });

            document.addEventListener('click', function (e) {
                var link = e.target.closest('a[href*="wa.me"], a[href*="api.whatsapp.com"]');
                if (!link) return;
                post('/track/event', {
                    type: 'whatsapp_click',
                    url: window.location.href,
                    title: document.title,
                    product_id: productId
                });
            });
        }

        window.invictaTrack = function (type, data) {
            data = data || {};
            data.type = type;
            data.url = data.url || window.location.href;
            data.title = data.title || document.title;
            post('/track/event', data);
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', start);
        } else {
            start();
        }
    })();
    </script>

    @stack('scripts')
</body>
</html>
