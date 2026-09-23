{{-- Promo Anillo cortesía: banner + popup + burbuja círculo --}}
{{-- Banner (componente reutilizable, también se muestra bajo el navbar) --}}
<x-promo-anillo-banner />

{{-- Popup --}}
<div id="promoAnilloModal" class="modal-overlay fixed inset-0 z-[110] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4" style="z-index:9999;background:rgba(0,0,0,.82);">
    <div class="modal-content relative w-full max-w-[360px] rounded-3xl overflow-hidden shadow-2xl bg-[#0a1628] border border-yellow-400/40" style="background:#0a1628;border:1px solid rgba(250,204,21,.4);border-radius:1.5rem;max-width:360px;width:100%;">
        <button type="button" onclick="closePromoAnillo()" aria-label="Minimizar"
            class="absolute top-3 right-3 z-10 w-8 h-8 flex items-center justify-center bg-white/10 hover:bg-white/20 text-white rounded-full transition-all">
            <i class="fa-solid fa-minus text-xs"></i>
        </button>
        <a href="/relojes?coleccion=Mini" onclick="minimizePromoAnillo()" class="block">
            <img src="{{ asset('images/promos/anillo-gratis-90k.jpg') }}"
                 alt="Recibe un anillo de cortesía con la compra de tu reloj - Invicta Costa Rica"
                 class="w-full h-auto"
                 onerror="this.style.display='none';document.getElementById('promoAnilloFallback').style.display='block'" />
            {{-- Diseño HTML (se ve mientras no subas el JPG) --}}
            <div id="promoAnilloFallback" style="display:none" class="px-6 pt-7 pb-6 text-center relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-36 h-36 bg-yellow-400/15 rounded-full blur-2xl"></div>
                <div class="absolute -bottom-12 -left-10 w-36 h-36 bg-blue-500/20 rounded-full blur-2xl"></div>
                <p class="text-[10px] font-black tracking-[0.3em] text-gray-400 uppercase mb-2">Invicta · Relojes originales</p>
                <span class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-yellow-400 text-gray-900 shadow-lg shadow-yellow-400/30 mb-3">
                    <i class="fa-solid fa-gift text-2xl"></i>
                </span>
                <p class="text-yellow-400 font-black text-4xl uppercase leading-[0.95] tracking-tight">Anillo<br><span class="text-white">de cortesía</span></p>
                <p class="text-gray-300 text-[11px] font-bold uppercase tracking-widest mt-2">Con la compra de tu reloj</p>
                <div class="flex items-center justify-center gap-3 mt-4 text-[10px] font-bold text-gray-300 uppercase">
                    <span><i class="fa-solid fa-shield-heart text-yellow-400 mr-1"></i>Garantía 6 meses</span>
                </div>
                <p class="text-gray-500 text-[10px] mt-2">No aplica para apartados · Pago contra entrega</p>
            </div>
        </a>
        <a href="/relojes?coleccion=Mini" onclick="minimizePromoAnillo()"
           class="flex items-center justify-center gap-2 bg-yellow-400 hover:bg-yellow-300 text-gray-900 font-black uppercase tracking-wide text-sm py-3.5 transition-colors">
            Ver anillos <i class="fa-solid fa-arrow-right text-xs"></i>
        </a>
    </div>
</div>

{{-- Burbuja: solo circulito --}}
<button id="promoAnilloBubble" type="button" onclick="openPromoAnillo()" aria-label="Ver promo anillo gratis"
    style="z-index:9998;"
    class="hidden fixed bottom-5 left-4 z-[105] w-12 h-12 items-center justify-center bg-yellow-400 hover:bg-yellow-300 text-gray-900 rounded-full shadow-2xl transition-all hover:scale-110">
    <i class="fa-solid fa-gift text-xl"></i>
    <span onclick="dismissPromoAnillo(event)" aria-label="Ocultar por hoy"
        class="absolute -top-1.5 -right-1.5 w-5 h-5 flex items-center justify-center bg-gray-900 hover:bg-black text-white rounded-full text-[10px] border border-white/30">✕</span>
</button>

<script>
(function () {
    var KEY = 'promoAnilloDismissed';
    var SHOWN_KEY = 'promoAnilloShown';
    function todayStr() { return new Date().toDateString(); }
    function dismissedToday() {
        try {
            var v = localStorage.getItem(KEY);
            if (!v) return false;
            return todayStr() === new Date(parseInt(v, 10)).toDateString();
        } catch (e) { return false; }
    }
    function shownToday() {
        try { return localStorage.getItem(SHOWN_KEY) === todayStr(); } catch (e) { return true; }
    }
    function markShown() {
        try { localStorage.setItem(SHOWN_KEY, todayStr()); } catch (e) {}
    }
    function showBubble() {
        var b = document.getElementById('promoAnilloBubble');
        if (!b || dismissedToday()) return;
        b.classList.remove('hidden');
        b.classList.add('flex');
    }
    function hideBubble() {
        var b = document.getElementById('promoAnilloBubble');
        if (!b) return;
        b.classList.add('hidden');
        b.classList.remove('flex');
    }
    window.openPromoAnillo = function () {
        var el = document.getElementById('promoAnilloModal');
        if (!el) return;
        hideBubble();
        el.classList.remove('hidden');
        el.classList.add('flex');
        document.body.style.overflow = 'hidden';
        requestAnimationFrame(function () { el.classList.add('active'); });
        if (typeof fbq !== 'undefined') { try { fbq('track', 'ViewContent', { content_name: 'Promo Anillo Cortesía' }); } catch (e) {} }
    };
    window.closePromoAnillo = function () { window.minimizePromoAnillo(); };
    window.minimizePromoAnillo = function () {
        var el = document.getElementById('promoAnilloModal');
        if (!el) return;
        el.classList.remove('active');
        document.body.style.overflow = '';
        setTimeout(function () { el.classList.add('hidden'); el.classList.remove('flex'); }, 250);
        showBubble();
    };
    window.dismissPromoAnillo = function (e) {
        if (e) e.stopPropagation();
        try { localStorage.setItem(KEY, String(Date.now())); } catch (err) {}
        hideBubble();
        var el = document.getElementById('promoAnilloModal');
        if (el) { el.classList.add('hidden'); el.classList.remove('flex'); el.classList.remove('active'); }
        document.body.style.overflow = '';
    };
    var el = document.getElementById('promoAnilloModal');
    if (el) el.addEventListener('click', function (e) { if (e.target === el) window.minimizePromoAnillo(); });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') window.minimizePromoAnillo();
    });
    setTimeout(function () {
        if (dismissedToday()) return;
        if (shownToday()) { showBubble(); return; }
        markShown();
        window.openPromoAnillo();
    }, 2500);
})();
</script>
