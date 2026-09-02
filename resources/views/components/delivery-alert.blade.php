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
        max-width: 21rem !important;
        max-height: calc(100vh - 2rem);
        overflow-y: auto;
        box-shadow: 0 24px 60px -12px rgba(0, 0, 0, 0.35) !important;
        border: 1px solid rgba(0, 196, 255, 0.25);
    }
    html.dark .promo-popup {
        background: #0a0f1c !important;
        border-color: rgba(0, 196, 255, 0.25);
    }

    .delivery-title {
        display: none !important;
    }
    .delivery-body {
        margin: 0 !important;
        padding: 0 !important;
    }

    .promo-header {
        position: relative;
        text-align: center;
        padding: 0.9rem 1.1rem 0.8rem;
        background: #0a0f1c;
    }
    .promo-x {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        width: 1.9rem;
        height: 1.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9999px;
        background: #00c4ff;
        color: #0a0f1c;
        border: none;
        cursor: pointer;
        transition: transform 0.15s ease, background-color 0.15s ease;
    }
    .promo-x:hover {
        transform: scale(1.1);
        background: #2ecfff;
    }
    .promo-x svg {
        width: 1rem;
        height: 1rem;
    }
    .promo-title {
        position: relative;
        margin: 0;
        font-size: 1.5rem;
        line-height: 1.1;
        font-weight: 900;
        color: #ffffff;
        letter-spacing: -0.02em;
    }
    .promo-title span {
        color: #00c4ff;
    }
    .promo-sub {
        position: relative;
        margin: 0.35rem 0 0;
        font-size: 0.9rem;
        line-height: 1.35;
        color: rgba(255, 255, 255, 0.85);
    }

    .promo-body {
        padding: 0.9rem 1.1rem 0.25rem;
    }
    .promo-products {
        display: flex;
        justify-content: center;
        gap: 0.7rem;
        margin-bottom: 0.9rem;
    }
    .promo-products img {
        width: 5rem;
        height: 5rem;
        object-fit: cover;
        border-radius: 0.85rem;
        background: #fff;
        border: 1px solid rgba(0, 0, 0, 0.08);
        box-shadow: 0 6px 16px -6px rgba(0, 0, 0, 0.25);
    }
    html.dark .promo-products img {
        border-color: rgba(255, 255, 255, 0.1);
    }
    .promo-cta {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        padding: 0.75rem 1rem;
        border-radius: 0.85rem;
        font-weight: 900;
        font-size: 0.9rem;
        letter-spacing: 0.02em;
        text-transform: uppercase;
        text-decoration: none;
        background: #00c4ff;
        color: #0a0f1c;
        box-shadow: 0 10px 24px -8px rgba(0, 196, 255, 0.6);
        transition: transform 0.15s ease, background-color 0.15s ease;
    }
    .promo-cta:hover {
        transform: translateY(-2px);
        background: #2ecfff;
        color: #0a0f1c;
    }
    .promo-cta svg {
        width: 1.1rem;
        height: 1.1rem;
    }
    .promo-note {
        margin: 0.6rem 0 0;
        font-size: 0.72rem;
        line-height: 1.4;
        text-align: center;
        color: #9ca3af;
    }
    html.dark .promo-note {
        color: #6b7280;
    }

    .promo-actions {
        padding: 0.4rem 1.1rem 1rem !important;
        margin: 0 !important;
    }
    .promo-confirm {
        border-radius: 0.85rem !important;
        font-weight: 800 !important;
        padding: 0.6rem 1.5rem !important;
        font-size: 0.85rem !important;
        width: 100%;
        background: transparent !important;
        color: #111827 !important;
        border: 1px solid #cbd5e1 !important;
        box-shadow: none !important;
        transition: transform 0.15s ease, background-color 0.15s ease;
    }
    html.dark .promo-confirm {
        color: #e5e7eb !important;
        border-color: rgba(255, 255, 255, 0.25) !important;
    }
    .promo-confirm:hover {
        transform: translateY(-1px);
        background: rgba(0, 0, 0, 0.04) !important;
    }
    html.dark .promo-confirm:hover {
        background: rgba(255, 255, 255, 0.06) !important;
    }

    @media (max-width: 480px) {
        .promo-popup {
            width: min(21rem, 92vw) !important;
            max-width: 21rem !important;
        }
        .promo-title {
            font-size: 1.4rem;
        }
    }
</style>
<script>
function deliveryAlert() {
    const ENTRY_HTML = `
        <div class="promo-header">
            <button type="button" class="promo-x" onclick="Swal.close()" aria-label="Cerrar">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <h3 class="promo-title">Un anillo <span>gratis</span></h3>
            <p class="promo-sub">Por la compra de 2 relojes.</p>
        </div>
        <div class="promo-body">
            <div class="promo-products">
                <img src="https://cdn.invictacostarica.com/relojes/49895.jpg" alt="Anillo Invicta" loading="lazy" />
                <img src="https://cdn.invictacostarica.com/relojes/48948.jpg" alt="Anillo Invicta" loading="lazy" />
                <img src="https://cdn.invictacostarica.com/relojes/49573.png" alt="Anillo Invicta" loading="lazy" />
            </div>
            <a href="https://invictacostarica.com/relojes?coleccion=Mini" class="promo-cta">
                Ver los anillos
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12l-7.5 7.5m6.75-7.5H3" />
                </svg>
            </a>
            <p class="promo-note">*El env&iacute;o se cobra por separado.</p>
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
                showCloseButton: false,
                showConfirmButton: true,
                confirmButtonText: 'Quizás después',
                background: document.documentElement.classList.contains('dark') ? '#0a0f1c' : '#fff',
                customClass: {
                    popup: 'promo-popup',
                    title: 'delivery-title',
                    htmlContainer: 'delivery-body',
                    actions: 'promo-actions',
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
