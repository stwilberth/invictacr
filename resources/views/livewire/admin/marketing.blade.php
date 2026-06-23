<div>
    <div class="flex gap-2 mb-6">
        <button wire:click="$set('activeTab', 'dashboard')" class="px-4 py-2 rounded-xl text-sm font-bold transition-all {{ $activeTab === 'dashboard' ? 'bg-[#00C4FF] text-[#0a0f1c]' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/5' }}">Dashboard</button>
        <button wire:click="$set('activeTab', 'tasks')" class="px-4 py-2 rounded-xl text-sm font-bold transition-all {{ $activeTab === 'tasks' ? 'bg-[#00C4FF] text-[#0a0f1c]' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/5' }}">Tareas</button>
        <button wire:click="$set('activeTab', 'content')" class="px-4 py-2 rounded-xl text-sm font-bold transition-all {{ $activeTab === 'content' ? 'bg-[#00C4FF] text-[#0a0f1c]' : 'bg-white dark:bg-[#0f172a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/5' }}">Contenido AI</button>
    </div>

    @if($activeTab === 'tasks')
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6 mb-6">
        <form wire:submit="createTask" class="flex gap-3">
            <input wire:model="taskTitle" type="text" placeholder="Nueva tarea..." class="flex-1 bg-gray-50 dark:bg-[#0a0f1c] border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm" />
            <button type="submit" class="bg-[#00C4FF] hover:bg-[#00b0e6] text-[#0a0f1c] font-black px-5 py-2.5 rounded-xl text-sm">Crear</button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
            <h3 class="font-black text-gray-900 dark:text-white mb-4 uppercase tracking-wider text-sm">Pendientes ({{ $pendingTasks->count() }})</h3>
            <div class="space-y-2">
                @foreach($pendingTasks as $task)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-white/5 rounded-xl">
                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $task->title }}</span>
                    <button wire:click="completeTask({{ $task->id }})" class="text-green-500 hover:text-green-400"><i class="fa-solid fa-check"></i></button>
                </div>
                @endforeach
            </div>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
            <h3 class="font-black text-gray-900 dark:text-white mb-4 uppercase tracking-wider text-sm">Completadas ({{ $completedTasks->count() }})</h3>
            <div class="space-y-2">
                @foreach($completedTasks as $task)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-white/5 rounded-xl opacity-60">
                    <span class="text-sm text-gray-500 line-through">{{ $task->title }}</span>
                    <button wire:click="deleteTask({{ $task->id }})" class="text-red-400 hover:text-red-500"><i class="fa-solid fa-trash"></i></button>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @elseif($activeTab === 'content')
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6">
        <p class="text-gray-500 dark:text-gray-400 text-sm">Generación de contenido con IA (próximamente).</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6 text-center">
            <p class="text-3xl font-black text-[#00C4FF]">{{ $pendingTasks->count() }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Tareas pendientes</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6 text-center">
            <p class="text-3xl font-black text-green-500">{{ $completedTasks->count() }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Tareas completadas</p>
        </div>
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-white/5 p-6 text-center">
            <p class="text-3xl font-black text-blue-500">{{ $tasks->count() }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Total tareas</p>
        </div>
    </div>
    @endif
</div>