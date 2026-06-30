import Swal from 'sweetalert2'

function dismissPromo() {
  localStorage.setItem('promo-dismissed', 'true')
  createBadge()
}

document.addEventListener('DOMContentLoaded', () => {
  if (localStorage.getItem('promo-dismissed')) {
    createBadge()
    return
  }

  Swal.fire({
    title: '🔥 10% de descuento',
    html: `<div style="text-align:center;line-height:1.8">
      <div style="font-size:1.1rem;margin-bottom:12px">Válido hasta el <strong>1 de julio</strong></div>
      <div style="color:#555;font-size:0.95rem">
        ✅ Entregas rápidas<br>
        ✅ 100% originales<br>
        ✅ Paga al recibir en el GAM
      </div>
    </div>`,
    showConfirmButton: true,
    confirmButtonText: 'Contactar',
    showCancelButton: true,
    cancelButtonText: 'Ahora no',
    confirmButtonColor: '#25D366',
    cancelButtonColor: '#6c757d',
    reverseButtons: true,
    buttonsStyling: false,
    customClass: {
      confirmButton:
        'inline-flex items-center gap-2 bg-green-600 text-white font-bold px-6 py-3 rounded-lg hover:bg-green-700 transition-all duration-300 mx-1',
      cancelButton:
        'inline-flex items-center gap-2 bg-gray-400 text-white font-bold px-6 py-3 rounded-lg hover:opacity-90 transition-all duration-300 mx-1',
      popup: 'rounded-2xl shadow-2xl',
    },
    didOpen: () => {
      const btn = Swal.getConfirmButton()
      if (btn) btn.innerHTML = '<i class="fab fa-whatsapp"></i> Contactar'
    },
  }).then((result) => {
    if (result.isConfirmed) {
      window.open('https://wa.me/50686711422?text=' + encodeURIComponent('Hola, me interesa el 10% de descuento hasta el 1 de julio'), '_blank')
    }
    dismissPromo()
  })
})

function createBadge() {
  const existing = document.getElementById('promo-badge')
  if (existing) return

  const badge = document.createElement('div')
  badge.id = 'promo-badge'
  badge.innerHTML = '🔥 10%'
  Object.assign(badge.style, {
    position: 'fixed',
    bottom: '20px',
    left: '20px',
    zIndex: '9999',
    background: '#00C4FF',
    color: 'white',
    padding: '10px 18px',
    borderRadius: '50px',
    fontWeight: 'bold',
    cursor: 'pointer',
    boxShadow: '0 4px 16px rgba(0,0,0,0.25)',
    fontSize: '15px',
    transition: 'transform 0.2s, box-shadow 0.2s',
    userSelect: 'none',
  })

  badge.addEventListener('mouseenter', () => {
    badge.style.transform = 'scale(1.08)'
    badge.style.boxShadow = '0 6px 20px rgba(0,0,0,0.35)'
  })
  badge.addEventListener('mouseleave', () => {
    badge.style.transform = 'scale(1)'
    badge.style.boxShadow = '0 4px 16px rgba(0,0,0,0.25)'
  })

  badge.addEventListener('click', () => {
    badge.remove()
    localStorage.removeItem('promo-dismissed')
    location.reload()
  })

  document.body.appendChild(badge)
}
