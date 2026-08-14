<div>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4">
            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-bold">Total</p>
            <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $total }}</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4">
            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-bold">Visibles en el sitio</p>
            <p class="text-2xl font-black text-green-500">{{ $activos }}</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-4">
            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-bold">Ocultos</p>
            <p class="text-2xl font-black text-red-500">{{ $total - $activos }}</p>
        </div>
    </div>

    {{-- Formulario de subida --}}
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-5 mb-6">
        <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight mb-4">
            <i class="fa-solid fa-cloud-arrow-up text-[#00C4FF] mr-1"></i> Subir video de reseña
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Archivo de video</label>
                <input id="review-video-file" type="file" accept="video/mp4,video/quicktime,video/webm,video/x-m4v,video/3gpp" class="block w-full text-sm text-gray-700 dark:text-gray-300 file:mr-3 file:rounded-xl file:border-0 file:bg-[#00C4FF] file:px-4 file:py-2 file:text-[#0a0f1c] file:font-bold file:text-xs hover:file:bg-[#00a3d6] cursor-pointer" />
                <p id="review-video-name" class="mt-1 text-xs text-gray-500 dark:text-gray-400 font-bold hidden"></p>
                <p id="review-video-error" class="mt-1 text-xs text-red-500 font-bold"></p>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Nombre / cliente (opcional)</label>
                <input wire:model="nombre" id="review-video-nombre" type="text" placeholder="Ej: María - Heredia" class="w-full bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
            </div>
        </div>
        <div class="mt-4 flex items-center gap-3">
            <button type="button" id="review-video-upload-btn" class="inline-flex items-center gap-2 bg-[#00C4FF] hover:bg-[#00a3d6] text-[#0a0f1c] rounded-xl font-extrabold uppercase tracking-tight text-xs px-5 py-2.5 transition-all hover:-translate-y-0.5 active:scale-95 shadow-sm hover:shadow-md">
                <i class="fa-solid fa-cloud-arrow-up"></i> Subir a Cloudflare Stream
            </button>
            <span id="review-video-progress" class="text-xs text-gray-500 dark:text-gray-400 font-bold hidden">
                <i class="fa-solid fa-spinner fa-spin"></i> Subiendo... <span id="review-video-progress-pct">0%</span>
            </span>
        </div>
        <div id="review-video-progress-bar" class="mt-3 h-2 rounded-full bg-gray-200 dark:bg-white/10 overflow-hidden hidden">
            <div id="review-video-progress-bar-fill" class="h-full bg-[#00C4FF] transition-all duration-300" style="width:0%"></div>
        </div>
        @if($uploadStatus === 'ok')
            <p class="mt-3 text-xs font-bold text-green-600 dark:text-green-400"><i class="fa-solid fa-circle-check"></i> {{ $uploadMessage }}</p>
        @elseif($uploadStatus === 'error')
            <p class="mt-3 text-xs font-bold text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation"></i> {{ $uploadMessage }}</p>
        @endif
    </div>

    {{-- Lista de videos --}}
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200 dark:border-white/5 flex items-center justify-between">
            <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight">
                <i class="fa-solid fa-list text-[#00C4FF] mr-1"></i> Videos existentes
            </h3>
            <span class="text-xs text-gray-500 dark:text-gray-400 font-bold">{{ $videos->total() }} total</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 p-5">
            @forelse($videos as $video)
            <div class="rounded-2xl border {{ $video->activo ? 'border-gray-200 dark:border-white/10' : 'border-gray-200 dark:border-white/5 opacity-60' }} overflow-hidden bg-gray-50 dark:bg-[#0a0f1c]">
                <div class="relative aspect-video bg-black">
                    <img src="https://{{ config('services.cloudflare.stream_customer_subdomain') }}.cloudflarestream.com/{{ $video->stream_uid }}/thumbnails/thumbnail.jpg" alt="{{ $video->nombre ?? 'Reseña' }}" class="w-full h-full object-cover" loading="lazy" />
                    <a href="https://{{ config('services.cloudflare.stream_customer_subdomain') }}.cloudflarestream.com/{{ $video->stream_uid }}/iframe" target="_blank" rel="noopener" class="absolute inset-0 flex items-center justify-center bg-black/30 hover:bg-black/10 transition-all group">
                        <div class="w-12 h-12 rounded-full bg-white/90 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-play text-gray-900 text-base ml-1"></i>
                        </div>
                    </a>
                </div>
                <div class="p-4">
                    <input wire:blur="updateNombre({{ $video->id }}, $event.target.value)" wire:keydown.enter="updateNombre({{ $video->id }}, $event.target.value)" type="text" value="{{ $video->nombre ?? '' }}" placeholder="Sin nombre" class="w-full bg-transparent font-bold text-gray-900 dark:text-white text-sm mb-2 focus:outline-none focus:ring-1 focus:ring-[#00C4FF]/50 rounded-lg px-2 py-1" />
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-1">
                            <button wire:click="moveUp({{ $video->id }})" wire:confirm="¿Mover arriba?" title="Subir" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-white/10 transition-colors">
                                <i class="fa-solid fa-arrow-up text-xs"></i>
                            </button>
                            <button wire:click="moveDown({{ $video->id }})" wire:confirm="¿Mover abajo?" title="Bajar" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-white/10 transition-colors">
                                <i class="fa-solid fa-arrow-down text-xs"></i>
                            </button>
                            <span class="text-[10px] text-gray-400 font-bold px-1">#{{ $video->orden }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <button wire:click="toggle({{ $video->id }})" title="{{ $video->activo ? 'Ocultar del sitio' : 'Mostrar en el sitio' }}" class="w-8 h-8 flex items-center justify-center rounded-lg {{ $video->activo ? 'text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20' : 'text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/20' }} transition-colors">
                                <i class="fa-solid {{ $video->activo ? 'fa-eye' : 'fa-eye-slash' }} text-sm"></i>
                            </button>
                            <button wire:click="delete({{ $video->id }})" wire:confirm="¿Eliminar este video? También se borrará de Cloudflare Stream." title="Eliminar" class="w-8 h-8 flex items-center justify-center rounded-lg text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                <i class="fa-solid fa-trash text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="md:col-span-2 xl:col-span-3 text-center text-gray-500 dark:text-gray-400 text-sm py-10">
                <i class="fa-solid fa-video text-2xl mb-2 block text-gray-300 dark:text-gray-600"></i>
                No hay videos de reseñas todavía. Sube el primero arriba.
            </div>
            @endforelse
        </div>
    </div>
    <div class="mt-4">{{ $videos->links() }}</div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const fileInput = document.getElementById('review-video-file');
            const nombreInput = document.getElementById('review-video-nombre');
            const nameEl = document.getElementById('review-video-name');
            const errorEl = document.getElementById('review-video-error');
            const uploadBtn = document.getElementById('review-video-upload-btn');
            const progressWrap = document.getElementById('review-video-progress');
            const progressPct = document.getElementById('review-video-progress-pct');
            const progressBar = document.getElementById('review-video-progress-bar');
            const progressFill = document.getElementById('review-video-progress-bar-fill');

            let selectedFile = null;

            fileInput.addEventListener('change', () => {
                selectedFile = fileInput.files[0] || null;
                errorEl.textContent = '';
                if (selectedFile) {
                    nameEl.textContent = selectedFile.name;
                    nameEl.classList.remove('hidden');
                } else {
                    nameEl.classList.add('hidden');
                }
            });

            uploadBtn.addEventListener('click', async () => {
                errorEl.textContent = '';

                if (!selectedFile) {
                    errorEl.textContent = 'Selecciona un archivo de video.';
                    return;
                }
                if (selectedFile.size > 200 * 1024 * 1024) {
                    errorEl.textContent = 'El video no debe superar 200MB.';
                    return;
                }

                uploadBtn.disabled = true;
                progressWrap.classList.remove('hidden');
                progressBar.classList.remove('hidden');
                progressFill.style.width = '0%';
                progressPct.textContent = '0%';

                try {
                    const res = await @this.call('getUploadUrl');
                    if (!res || res.error) {
                        throw new Error((res && res.error) || 'No se pudo generar el enlace de subida.');
                    }
                    await uploadToCloudflare(res.uploadURL, selectedFile);
                    await @this.call('store', res.uid, nombreInput.value || '');
                    uploadBtn.disabled = false;
                    progressWrap.classList.add('hidden');
                    progressBar.classList.add('hidden');
                    selectedFile = null;
                    fileInput.value = '';
                    nameEl.classList.add('hidden');
                } catch (e) {
                    errorEl.textContent = e.message || 'Error al subir el video.';
                    uploadBtn.disabled = false;
                    progressWrap.classList.add('hidden');
                    progressBar.classList.add('hidden');
                }
            });

            function uploadToCloudflare(uploadURL, file) {
                return new Promise((resolve, reject) => {
                    const fd = new FormData();
                    fd.append('file', file);
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', uploadURL);
                    xhr.upload.onprogress = (e) => {
                        if (e.lengthComputable) {
                            const pct = Math.round((e.loaded / e.total) * 100);
                            progressFill.style.width = pct + '%';
                            progressPct.textContent = pct + '%';
                        }
                    };
                    xhr.onload = () => {
                        if (xhr.status >= 200 && xhr.status < 300) {
                            resolve();
                        } else {
                            let msg = 'Error al subir el video (HTTP ' + xhr.status + ').';
                            try {
                                const data = JSON.parse(xhr.responseText);
                                if (data && data.errors && data.errors.length && data.errors[0].message) {
                                    msg = data.errors[0].message;
                                }
                            } catch (e) {}
                            reject(new Error(msg));
                        }
                    };
                    xhr.onerror = () => reject(new Error('Error de conexión al subir el video. Reintenta con mejor señal.'));
                    xhr.send(fd);
                });
            }
        });
    </script>
    @endpush
</div>
