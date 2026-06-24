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
    <meta property="og:image" content="{{ $ogImage ?? asset('logo.png') }}" />
    <meta property="og:locale" content="es_CR" />

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ $title ?? '' }} | Invicta Costa Rica" />
    <meta name="twitter:description" content="{{ $description ?? 'Invicta Costa Rica - Relojes de alta calidad con los mejores precios. Pago contra entrega en GAM y envío gratis a todo el país.' }}" />
    <meta name="twitter:image" content="{{ $ogImage ?? asset('logo.png') }}" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" media="print" onload="this.media='all'" />
    <noscript>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    </noscript>

    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
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
            "logo": "{{ asset('logo.png') }}",
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
    {{ $head ?? '' }}
</head>
<body class="bg-white text-gray-900 dark:bg-[#121212] dark:text-gray-100">
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MFKHNJ9V" height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>

    <x-navbar :q="$q ?? null" />

    <main class="min-h-screen">
        {{ $slot }}
    </main>

    <x-footer />
    @unless($hideWhatsApp ?? false)
        <x-whatsapp-button />
    @endunless

    @stack('scripts')
</body>
</html>
