<div id="cookie-banner"
     class="fixed bottom-0 left-0 right-0 z-[90] bg-white/95 dark:bg-[#0a0f1c]/95 backdrop-blur-md border-t border-gray-200 dark:border-white/10 shadow-[0_-10px_30px_rgba(0,0,0,0.15)]"
     role="dialog"
     aria-label="Aviso de cookies">
    <div class="max-w-6xl mx-auto px-4 py-4 flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-6">
        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 flex-1">
            Usamos cookies propias para recordarte qué productos viste y mejorar la tienda.
            <a href="{{ route('privacidad') }}" class="text-[#00A3D6] dark:text-[#00C4FF] font-bold underline">Más info</a>.
        </p>
        <button type="button" id="cookie-close"
                class="px-4 py-2 text-xs sm:text-sm font-bold rounded-xl bg-[#00C4FF] hover:bg-[#00b0e6] text-[#0a0f1c] transition-colors shrink-0">
            Aceptar
        </button>
    </div>
</div>

<script>
(function () {
    var HIDE_COOKIE = 'cookie_banner_closed';
    var CONSENT_COOKIE = 'invicta_consent';

    function isHidden() {
        return document.cookie.indexOf(HIDE_COOKIE + '=1') !== -1;
    }

    function hide() {
        var days = 30;
        var expires = new Date(Date.now() + days * 864e5).toUTCString();
        document.cookie = HIDE_COOKIE + '=1; expires=' + expires + '; path=/; SameSite=Lax' + (location.protocol === 'https:' ? '; Secure' : '');
        // Establecer consentimiento para el tracking
        document.cookie = CONSENT_COOKIE + '=accepted; expires=' + expires + '; path=/; SameSite=Lax' + (location.protocol === 'https:' ? '; Secure' : '');
    }

    var banner = document.getElementById('cookie-banner');

    if (isHidden()) {
        banner.style.display = 'none';
    }

    document.getElementById('cookie-close').addEventListener('click', function () {
        hide();
        banner.style.display = 'none';
    });
})();
</script>
