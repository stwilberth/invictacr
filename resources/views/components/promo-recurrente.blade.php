{{-- Promo 10% recurrente: banner + popup + burbuja círculo (solo visitantes recurrentes) --}}
{{-- Banner (componente reutilizable, también se muestra bajo el navbar) --}}
<x-promo-recurrente-banner />

@php
    $refCode = strtoupper(substr((string) ($visitorUuid ?? ''), -6));
    $waLink = 'https://wa.me/50686711422?text=' . urlencode('¡Hola! Vi la promo del 10% de descuento y quiero más información' . ($refCode ? ". Mi código: {$refCode}" : ''));
@endphp

{{-- Popup --}}
<div id="promoRecurrenteModal" class="modal-overlay fixed inset-0 z-[110] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4" style="z-index:9999;background:rgba(0,0,0,.82);">
    <div class="modal-content relative w-full max-w-[360px] rounded-3xl overflow-hidden shadow-2xl bg-[#0a1628] border border-[#00C4FF]/40" style="background:#0a1628;border:1px solid rgba(0,196,255,.4);border-radius:1.5rem;max-width:360px;width:100%;">
        <button type="button" onclick="closePromoRecurrente()" aria-label="Minimizar"
            class="absolute top-3 right-3 z-10 w-8 h-8 flex items-center justify-center bg-white/10 hover:bg-white/20 text-white rounded-full transition-all">
            <i class="fa-solid fa-minus text-xs"></i>
        </button>
        <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer" onclick="minimizePromoRecurrente()" class="block">
            <div class="px-6 pt-7 pb-6 text-center relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-36 h-36 bg-[#00C4FF]/15 rounded-full blur-2xl"></div>
                <div class="absolute -bottom-12 -left-10 w-36 h-36 bg-blue-500/20 rounded-full blur-2xl"></div>
                <p class="text-[10px] font-black tracking-[0.3em] text-gray-400 uppercase mb-2">Invicta · Descuento especial</p>
                <span class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-[#00C4FF] text-[#0a0f1c] shadow-lg shadow-[#00C4FF]/30 mb-3">
                    <i class="fa-solid fa-tag text-2xl"></i>
                </span>
                <p class="text-[#00C4FF] font-black text-4xl uppercase leading-[0.95] tracking-tight">10%<br><span class="text-white">de descuento</span></p>
                <p class="text-gray-300 text-[11px] font-bold uppercase tracking-widest mt-2">Solo por hoy</p>
                @if($refCode)
                <p class="mt-3 inline-block bg-white/10 border border-dashed border-[#00C4FF]/60 rounded-lg px-4 py-1.5 text-[#00C4FF] font-black tracking-[0.25em] text-sm">TU CÓDIGO: {{ $refCode }}</p>
                @endif
                <div class="flex items-center justify-center gap-3 mt-4 text-[10px] font-bold text-gray-300 uppercase">
                    <span><i class="fa-solid fa-shield-heart text-[#00C4FF] mr-1"></i>Garantía 6 meses</span>
                </div>
                <p class="text-gray-500 text-[10px] mt-2">No aplica para apartados · Pago contra entrega</p>
            </div>
        </a>
        <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer" onclick="minimizePromoRecurrente()"
           class="flex items-center justify-center gap-2 bg-[#00C4FF] hover:bg-[#00a3d6] text-[#0a0f1c] font-black uppercase tracking-wide text-sm py-3.5 transition-colors">
            Reclamar por WhatsApp <i class="fa-brands fa-whatsapp text-base"></i>
        </a>
    </div>
</div>

{{-- Burbuja: solo circulito --}}
<button id="promoRecurrenteBubble" type="button" onclick="openPromoRecurrente()" aria-label="Ver mi 10% de descuento"
    style="z-index:9998;"
    class="hidden fixed bottom-5 left-4 z-[105] w-12 h-12 items-center justify-center bg-[#00C4FF] hover:bg-[#00a3d6] text-[#0a0f1c] rounded-full shadow-2xl transition-all hover:scale-110">
    <i class="fa-solid fa-tag text-xl"></i>
    <span onclick="dismissPromoRecurrente(event)" aria-label="Ocultar por hoy"
        class="absolute -top-1.5 -right-1.5 w-5 h-5 flex items-center justify-center bg-gray-900 hover:bg-black text-white rounded-full text-[10px] border border-white/30">✕</span>
</button>

<script>
(function () {
    var KEY = 'promoRecurrenteDismissed';
    var SHOWN_KEY = 'promoRecurrenteShown';
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
        var b = document.getElementById('promoRecurrenteBubble');
        if (!b || dismissedToday()) return;
        b.classList.remove('hidden');
        b.classList.add('flex');
    }
    function hideBubble() {
        var b = document.getElementById('promoRecurrenteBubble');
        if (!b) return;
        b.classList.add('hidden');
        b.classList.remove('flex');
    }
    window.openPromoRecurrente = function () {
        var el = document.getElementById('promoRecurrenteModal');
        if (!el) return;
        hideBubble();
        el.classList.remove('hidden');
        el.classList.add('flex');
        document.body.style.overflow = 'hidden';
        requestAnimationFrame(function () { el.classList.add('active'); });
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({ event: 'view_promo_recurrente', eventModel: { currency: 'CRC', items: [{ item_name: 'Promo Recurrente 10%' }] } });
    };
    window.closePromoRecurrente = function () { window.minimizePromoRecurrente(); };
    window.minimizePromoRecurrente = function () {
        var el = document.getElementById('promoRecurrenteModal');
        if (!el) return;
        el.classList.remove('active');
        document.body.style.overflow = '';
        setTimeout(function () { el.classList.add('hidden'); el.classList.remove('flex'); }, 250);
        showBubble();
    };
    window.dismissPromoRecurrente = function (e) {
        if (e) e.stopPropagation();
        try { localStorage.setItem(KEY, String(Date.now())); } catch (err) {}
        hideBubble();
        var el = document.getElementById('promoRecurrenteModal');
        if (el) { el.classList.add('hidden'); el.classList.remove('flex'); el.classList.remove('active'); }
        document.body.style.overflow = '';
    };
    var el = document.getElementById('promoRecurrenteModal');
    if (el) el.addEventListener('click', function (e) { if (e.target === el) window.minimizePromoRecurrente(); });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') window.minimizePromoRecurrente();
    });
    setTimeout(function () {
        if (dismissedToday()) return;
        if (shownToday()) { showBubble(); return; }
        markShown();
        window.openPromoRecurrente();
    }, 2500);
})();
</script>
