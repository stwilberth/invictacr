<div id="delivery-alert-wrapper"
     x-data="deliveryAlert()"
     x-init="init()"
     x-show="minimized"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 -translate-x-3"
     x-transition:enter-end="opacity-100 translate-x-0"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100 translate-x-0"
     x-transition:leave-end="opacity-0 -translate-x-3"
     class="fixed bottom-4 left-4 md:bottom-6 md:left-6 z-50 transition-[bottom] duration-300"
     x-cloak>
    <button @click="showAlert()"
             aria-label="Promoción: un anillo gratis"
             class="promo-fab relative flex items-center justify-center w-14 h-14 md:w-16 md:h-16 rounded-full transition-all duration-200 hover:-translate-y-0.5 active:scale-95">
        <span class="promo-fab-glow absolute inset-0 rounded-full"></span>
        <svg xmlns="http://www.w3.org/2000/svg" class="relative w-6 h-6 md:w-7 md:h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 1 0 9.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1 1 14.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
        </svg>
        <span class="promo-fab-badge">GRATIS</span>
    </button>
</div>

@push('scripts')
<style>
    .promo-fab {
        background: #00c4ff;
        color: #0a0f1c;
        box-shadow: 0 10px 30px -6px rgba(0, 196, 255, 0.55);
    }
    .promo-fab-glow {
        animation: promo-pulse 2.2s infinite;
    }
    @keyframes promo-pulse {
        0%   { box-shadow: 0 0 0 0 rgba(0, 196, 255, 0.55); }
        70%  { box-shadow: 0 0 0 16px rgba(0, 196, 255, 0); }
        100% { box-shadow: 0 0 0 0 rgba(0, 196, 255, 0); }
    }
    .promo-fab-badge {
        position: absolute;
        top: -8px;
        right: -10px;
        background: #ff2d55;
        color: #fff;
        font-size: 0.52rem;
        font-weight: 900;
        letter-spacing: 0.05em;
        padding: 0.2rem 0.5rem;
        border-radius: 9999px;
        box-shadow: 0 4px 12px -2px rgba(255, 45, 85, 0.6);
        animation: promo-bounce 1.6s ease-in-out infinite;
    }
    @keyframes promo-bounce {
        0%, 100% { transform: translateY(0); }
        50%      { transform: translateY(-4px); }
    }

    .promo-popup {
        border-radius: 1.25rem !important;
        padding: 0 !important;
        max-width: 20rem !important;
        max-height: calc(100vh - 2rem);
        overflow-y: auto;
        box-shadow: 0 24px 60px -12px rgba(0, 0, 0, 0.35) !important;
        border: 1px solid rgba(0, 196, 255, 0.25);
    }
    html.dark .promo-popup {
        background: #0a0f1c !important;
        border-color: rgba(0, 196, 255, 0.25);
    }

    .promo-header {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 1.25rem 1.25rem 1rem;
        background: #0a0f1c;
    }
    .promo-header::after {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(120px 120px at 50% -20px, rgba(0, 196, 255, 0.25), transparent 70%);
        pointer-events: none;
    }
    .promo-icon-wrap {
        position: relative;
        width: 3rem;
        height: 3rem;
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #00c4ff;
        background: rgba(0, 196, 255, 0.12);
        border: 1px solid rgba(0, 196, 255, 0.35);
        margin-bottom: 0.6rem;
        box-shadow: 0 0 0 6px rgba(0, 196, 255, 0.06);
    }
    .promo-icon-wrap svg {
        width: 1.5rem;
        height: 1.5rem;
    }
    .promo-pill {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.62rem;
        font-weight: 900;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: #0a0f1c;
        background: #00c4ff;
        padding: 0.3rem 0.8rem;
        border-radius: 9999px;
    }
    .promo-title {
        position: relative;
        margin: 0.6rem 0 0.3rem;
        font-size: 1.25rem;
        line-height: 1.05;
        font-weight: 900;
        color: #ffffff;
        letter-spacing: -0.02em;
        text-transform: uppercase;
    }
    .promo-title span {
        color: #00c4ff;
    }
    .promo-sub {
        position: relative;
        margin: 0;
        font-size: 0.75rem;
        line-height: 1.4;
        color: rgba(255, 255, 255, 0.7);
    }

    .promo-body {
        padding: 1rem 1.25rem 0.25rem;
    }
    .promo-products {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    .promo-products img {
        width: 3.25rem;
        height: 3.25rem;
        object-fit: cover;
        border-radius: 0.75rem;
        background: #fff;
        border: 1px solid rgba(0, 0, 0, 0.08);
        box-shadow: 0 6px 16px -6px rgba(0, 0, 0, 0.25);
        transition: transform 0.15s ease;
    }
    html.dark .promo-products img {
        border-color: rgba(255, 255, 255, 0.1);
    }
    .promo-products img:hover {
        transform: translateY(-3px) scale(1.04);
    }
    .promo-perks {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    .promo-perk {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.68rem;
        font-weight: 700;
        color: #111827;
        background: rgba(0, 0, 0, 0.03);
        border: 1px solid rgba(0, 0, 0, 0.06);
        padding: 0.45rem 0.6rem;
        border-radius: 0.7rem;
    }
    html.dark .promo-perk {
        color: #e5e7eb;
        background: rgba(255, 255, 255, 0.04);
        border-color: rgba(255, 255, 255, 0.08);
    }
    .promo-perk svg {
        flex-shrink: 0;
        width: 1rem;
        height: 1rem;
        color: #00c4ff;
    }
    .promo-cta {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        padding: 0.7rem 1.25rem;
        border-radius: 0.8rem;
        font-weight: 900;
        font-size: 0.78rem;
        letter-spacing: 0.02em;
        text-transform: uppercase;
        text-decoration: none;
        background: #00c4ff;
        color: #0a0f1c;
        box-shadow: 0 10px 24px -8px rgba(0, 196, 255, 0.6);
        transition: transform 0.15s ease, background-color 0.15s ease, box-shadow 0.15s ease;
    }
    .promo-cta:hover {
        transform: translateY(-2px);
        background: #2ecfff;
        color: #0a0f1c;
        box-shadow: 0 14px 30px -8px rgba(0, 196, 255, 0.7);
    }
    .promo-cta:active {
        transform: scale(0.98);
    }
    .promo-cta svg {
        width: 1rem;
        height: 1rem;
    }
    .promo-note {
        margin: 0.8rem 0 0;
        font-size: 0.68rem;
        line-height: 1.4;
        text-align: center;
        font-style: italic;
        color: #9ca3af;
    }
    html.dark .promo-note {
        color: #6b7280;
    }

    .promo-actions {
        padding: 0.5rem 1.25rem 1.25rem !important;
        margin: 0 !important;
    }
    .promo-confirm {
        border-radius: 0.8rem !important;
        font-weight: 800 !important;
        letter-spacing: -0.01em;
        padding: 0.6rem 1.5rem !important;
        font-size: 0.74rem !important;
        width: 100%;
        background: transparent !important;
        color: #9ca3af !important;
        border: 1px solid rgba(0, 0, 0, 0.08);
        box-shadow: none !important;
        transition: transform 0.15s ease, color 0.15s ease;
    }
    html.dark .promo-confirm {
        color: rgba(255, 255, 255, 0.5) !important;
        border-color: rgba(255, 255, 255, 0.1);
    }
    .promo-confirm:hover {
        transform: translateY(-1px);
        color: #111827 !important;
    }
    html.dark .promo-confirm:hover {
        color: #fff !important;
    }
    .promo-close {
        color: #9ca3af !important;
        font-size: 1.15rem !important;
    }
    html.dark .promo-close {
        color: rgba(255, 255, 255, 0.5) !important;
    }

    @media (max-width: 480px) {
        .promo-popup {
            width: min(20rem, 92vw) !important;
            max-width: 20rem !important;
        }
        .promo-title {
            font-size: 1.15rem;
        }
    }
</style>
<script>
function deliveryAlert() {
    const ENTRY_HTML = `
        <div class="promo-header">
            <span class="promo-icon-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 1 0 9.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1 1 14.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                </svg>
            </span>
            <span class="promo-pill">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:0.7rem;height:0.7rem;">
                    <path fill-rule="evenodd" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456Z" clip-rule="evenodd" />
                </svg>
                Promo especial
            </span>
            <h3 class="promo-title">Un anillo <span>gratis</span></h3>
            <p class="promo-sub">Llevate un anillo de regalo por la compra de dos relojes.</p>
        </div>
        <div class="promo-body">
            <div class="promo-products">
                <img src="https://cdn.invictacostarica.com/relojes/49895.jpg" alt="Anillo Invicta" loading="lazy" />
                <img src="https://cdn.invictacostarica.com/relojes/48948.jpg" alt="Anillo Invicta" loading="lazy" />
                <img src="https://cdn.invictacostarica.com/relojes/49573.png" alt="Anillo Invicta" loading="lazy" />
            </div>
            <div class="promo-perks">
                <span class="promo-perk">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    2 relojes
                </span>
                <span class="promo-perk">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    1 anillo
                </span>
            </div>
            <a href="https://invictacostarica.com/relojes?coleccion=Mini" class="promo-cta">
                Ver los anillos
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12l-7.5 7.5m6.75-7.5H3" />
                </svg>
            </a>
            <p class="promo-note">*El env&iacute;o se cobra por separado en esta oferta.</p>
        </div>
    `;

    return {
        minimized: false,

        init() {
            if (localStorage.getItem('delivery_alert_minimized') === 'true') {
                this.minimized = true;
            } else {
                setTimeout(() => this.showAlert(), 1500);
            }
            this.watchCookieBanner();
        },

        watchCookieBanner() {
            const wrapper = document.getElementById('delivery-alert-wrapper');
            const cookieBanner = document.getElementById('cookie-banner');
            if (!wrapper || !cookieBanner) return;

            const baseBottom = window.innerWidth >= 768 ? 24 : 16; // md:bottom-6 / bottom-4
            const gap = 12;

            const reposition = () => {
                const visible = cookieBanner.offsetParent !== null
                    && getComputedStyle(cookieBanner).display !== 'none';
                wrapper.style.bottom = visible
                    ? (cookieBanner.offsetHeight + baseBottom + gap) + 'px'
                    : baseBottom + 'px';
            };

            reposition();
            window.addEventListener('resize', reposition);
            new MutationObserver(reposition).observe(cookieBanner, {
                attributes: true,
                attributeFilter: ['style', 'class'],
            });
        },

        showAlert() {
            const self = this;

            Swal.fire({
                title: '',
                html: ENTRY_HTML,
                icon: null,
                showCloseButton: true,
                showConfirmButton: true,
                confirmButtonText: 'Quizás después',
                background: document.documentElement.classList.contains('dark') ? '#0a0f1c' : '#fff',
                customClass: {
                    popup: 'promo-popup',
                    title: 'delivery-title',
                    htmlContainer: 'delivery-body',
                    actions: 'promo-actions',
                    closeButton: 'promo-close',
                    confirmButton: 'promo-confirm',
                },
                didClose: () => {
                    self.minimized = true;
                    localStorage.setItem('delivery_alert_minimized', 'true');
                },
            });
        },
    };
}
</script>
@endpush
