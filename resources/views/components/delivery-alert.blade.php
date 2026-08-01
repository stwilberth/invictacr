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
            aria-label="Información de entrega"
            class="flex items-center justify-center w-12 h-12 md:w-14 md:h-14 bg-[#0a0f1c] dark:bg-[#00C4FF] text-[#00C4FF] dark:text-[#0a0f1c] rounded-full shadow-[0_8px_24px_rgba(0,0,0,0.3)] dark:shadow-[0_8px_24px_rgba(0,196,255,0.35)] border border-white/10 dark:border-transparent transition-all duration-200 hover:-translate-y-0.5 active:scale-95">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 md:w-6 md:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
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
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                    </svg>
                </span>
                <div class="flex-1">
                    <p class="delivery-entry-title">Recibe hoy mismo*</p>
                    <p class="delivery-entry-desc">*En el &aacute;rea central de Costa Rica, y seg&uacute;n disponibilidad de los mensajeros.</p>
                </div>
            </div>
            <div class="delivery-entry">
                <span class="delivery-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m9.75 1.5h1.125c.621 0 1.125-.504 1.125-1.125V9.75c0-.621-.504-1.125-1.125-1.125H4.125C3.504 8.625 3 9.129 3 9.75v6.75c0 .621.504 1.125 1.125 1.125H6" />
                    </svg>
                </span>
                <div class="flex-1">
                    <p class="delivery-entry-title">Pago contra entrega</p>
                    <p class="delivery-entry-desc">Disponible en la zona central de Costa Rica.</p>
                </div>
            </div>
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
                title: 'Entrega inmediata',
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
