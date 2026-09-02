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
             aria-label="Promoción"
             class="flex items-center justify-center w-12 h-12 md:w-14 md:h-14 bg-[#0a0f1c] dark:bg-[#00C4FF] text-[#00C4FF] dark:text-[#0a0f1c] rounded-full shadow-[0_8px_24px_rgba(0,0,0,0.3)] dark:shadow-[0_8px_24px_rgba(0,196,255,0.35)] border border-white/10 dark:border-transparent transition-all duration-200 hover:-translate-y-0.5 active:scale-95">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 md:w-6 md:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 1 0 9.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1 1 14.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
        </svg>
    </button>
</div>

@push('scripts')
<style>
    .delivery-popup {
        border-radius: 1.25rem !important;
        padding: 1.75rem !important;
        max-width: 24rem !important;
        box-shadow: 0 20px 50px -12px rgba(0, 0, 0, 0.25) !important;
        border: 1px solid rgba(0, 0, 0, 0.08);
    }
    html.dark .delivery-popup {
        background: #0a0f1c !important;
        border-color: rgba(255, 255, 255, 0.1);
    }
    .delivery-title {
        font-size: 1.05rem !important;
        font-weight: 800 !important;
        color: #0a0f1c;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        padding: 0 0 0.25rem !important;
    }
    html.dark .delivery-title {
        color: #fff;
    }
    .delivery-body {
        padding: 0.5rem 0 0 !important;
        margin: 0 !important;
    }
    .delivery-actions {
        padding: 1.25rem 0 0 !important;
        margin: 0 !important;
    }
    .delivery-confirm {
        border-radius: 0.75rem !important;
        font-weight: 800 !important;
        text-transform: uppercase;
        letter-spacing: -0.01em;
        padding: 0.75rem 1.5rem !important;
        font-size: 0.8rem !important;
        width: 100%;
        background: #00c4ff !important;
        color: #0a0f1c !important;
        box-shadow: none !important;
        transition: transform 0.15s ease, background-color 0.15s ease;
    }
    .delivery-confirm:hover {
        transform: translateY(-2px);
        background: #00a3d6 !important;
    }
    .delivery-confirm:active {
        transform: scale(0.97);
    }
    .delivery-close {
        color: #9ca3af !important;
        font-size: 1.15rem !important;
    }
    html.dark .delivery-close {
        color: rgba(255, 255, 255, 0.5) !important;
    }
    .delivery-entry {
        display: flex;
        gap: 0.75rem;
        align-items: flex-start;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.07);
    }
    html.dark .delivery-entry {
        border-bottom-color: rgba(255, 255, 255, 0.08);
    }
    .delivery-entry:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    .delivery-icon {
        flex-shrink: 0;
        width: 2rem;
        height: 2rem;
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eaf9ff;
        color: #00a3d6;
    }
    html.dark .delivery-icon {
        background: rgba(0, 196, 255, 0.12);
        color: #00c4ff;
    }
    .delivery-entry-title {
        font-weight: 700;
        color: #111827;
        font-size: 0.875rem;
        line-height: 1.2;
    }
    html.dark .delivery-entry-title {
        color: #f3f4f6;
    }
    .delivery-entry-desc {
        font-size: 0.7rem;
        line-height: 1.4;
        color: #6b7280;
        margin-top: 0.15rem;
    }
    html.dark .delivery-entry-desc {
        color: #9ca3af;
    }
    .delivery-entry-note {
        font-size: 0.68rem;
        line-height: 1.4;
        color: #9ca3af;
        margin-top: 0.4rem;
        font-style: italic;
    }
    html.dark .delivery-entry-note {
        color: #6b7280;
    }
    .delivery-entry-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-top: 0.75rem;
        width: 100%;
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        font-weight: 800;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: -0.01em;
        text-decoration: none;
        background: #00c4ff;
        color: #0a0f1c;
        transition: transform 0.15s ease, background-color 0.15s ease;
    }
    .delivery-entry-link:hover {
        transform: translateY(-2px);
        background: #00a3d6;
        color: #0a0f1c;
    }
    .delivery-entry-link:active {
        transform: scale(0.97);
    }
    @media (max-width: 480px) {
        .delivery-popup {
            padding: 1.25rem !important;
            width: 90vw !important;
            max-width: 22rem !important;
        }
        .delivery-title {
            font-size: 0.95rem !important;
        }
    }
</style>
<script>
function deliveryAlert() {
    const ENTRY_HTML = `
        <div class="text-left">
            <div class="delivery-entry">
                <span class="delivery-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.25 6.087c0-.355.186-.676.401-.959.221-.29.349-.634.349-1.003 0-1.036-1.007-1.875-2.25-1.875s-2.25.84-2.25 1.875c0 .369.128.713.349 1.003.215.283.401.604.401.959v0a.64.64 0 0 1-.657.643 48.39 48.39 0 0 1-4.163-.3c.186 1.613.293 3.25.315 4.907a.656.656 0 0 1-.658.663v0c-.355 0-.676-.186-.959-.401a1.647 1.647 0 0 0-1.003-.349c-1.036 0-1.875 1.007-1.875 2.25s.84 2.25 1.875 2.25c.369 0 .713-.128 1.003-.349.283-.215.604-.401.959-.401v0c.31 0 .555.26.532.57a48.039 48.039 0 0 1-.642 5.056c1.518.19 3.058.309 4.616.354a.64.64 0 0 0 .657-.643v0c0-.355-.186-.676-.401-.959a1.647 1.647 0 0 1-.349-1.003c0-1.035 1.008-1.875 2.25-1.875 1.243 0 2.25.84 2.25 1.875 0 .369-.128.713-.349 1.003-.215.283-.4.604-.4.959v0c0 .333.277.599.61.58a48.1 48.1 0 0 0 5.427-.63 48.05 48.05 0 0 0 .582-4.717.532.532 0 0 0-.533-.57v0c-.355 0-.676.186-.959.401-.29.221-.634.349-1.003.349-1.035 0-1.875-1.007-1.875-2.25s.84-2.25 1.875-2.25c.37 0 .713.128 1.003.349.283.215.604.401.959.401v0a.656.656 0 0 0 .658-.663 48.422 48.422 0 0 0-.37-5.36c-1.886.342-3.81.574-5.766.689a.578.578 0 0 1-.61-.58v0z" />
                    </svg>
                </span>
                <div class="flex-1">
                    <p class="delivery-entry-title">Un anillo gratis</p>
                    <p class="delivery-entry-desc">Llevate un anillo de regalo por la compra de dos relojes.</p>
                    <p class="delivery-entry-note">*El env&iacute;o se cobra por separado en esta oferta.</p>
                </div>
            </div>
            <a href="https://invictacostarica.com/relojes?coleccion=Mini"
               class="delivery-entry-link">Ver los anillos</a>
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
                title: 'Promo del momento',
                html: ENTRY_HTML,
                icon: null,
                showCloseButton: true,
                showConfirmButton: true,
                confirmButtonText: 'Entendido',
                background: document.documentElement.classList.contains('dark') ? '#0a0f1c' : '#fff',
                customClass: {
                    popup: 'delivery-popup',
                    title: 'delivery-title',
                    htmlContainer: 'delivery-body',
                    actions: 'delivery-actions',
                    closeButton: 'delivery-close',
                    confirmButton: 'delivery-confirm',
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
