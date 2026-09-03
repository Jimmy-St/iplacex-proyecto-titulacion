@props(['ticket'])

<div class="relative">
    <button @click="if (status !== 'COMPLETADO') showStatusDropdown = !showStatusDropdown" 
            @click.away="showStatusDropdown = false"
            :disabled="status === 'COMPLETADO'"
            class="flex items-center justify-between w-36 px-3 py-1.5 rounded-lg border text-xs font-semibold transition-colors shadow-sm"
            :class="{
                'bg-amber-50 border-amber-300 text-amber-800': status === 'PENDIENTE',
                'bg-purple-50 border-purple-300 text-purple-800': status === 'PREPARANDO',
                'bg-emerald-50 border-emerald-300 text-emerald-800 cursor-not-allowed': status === 'COMPLETADO'
            }">
        <div class="flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full"
                    :class="{
                        'bg-amber-500 animate-pulse': status === 'PENDIENTE',
                        'bg-purple-600 animate-pulse': status === 'PREPARANDO',
                        'bg-emerald-600': status === 'COMPLETADO'
                    }"></span>
            <span x-text="status"></span>
        </div>
        <i data-lucide="chevron-down" class="w-3.5 h-3.5 opacity-60" x-show="status !== 'COMPLETADO'" style="stroke-width:2"></i>
    </button>

    <div x-show="showStatusDropdown && status !== 'COMPLETADO'" 
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            class="absolute right-0 mt-2 w-36 rounded-xl bg-white border border-purple-200 shadow-xl p-1 z-50 space-y-0.5"
            style="display: none;">
        <button @click="changeStatus('PENDIENTE')" class="w-full text-left px-3 py-2 rounded-lg text-xs font-semibold text-amber-800 bg-amber-50/60 hover:bg-amber-100/80 transition-colors">PENDIENTE</button>
        <button @click="changeStatus('PREPARANDO')" class="w-full text-left px-3 py-2 rounded-lg text-xs font-semibold text-purple-800 bg-purple-50/60 hover:bg-purple-100/80 transition-colors">PREPARANDO</button>
        <button @click="changeStatus('COMPLETADO')" class="w-full text-left px-3 py-2 rounded-lg text-xs font-semibold text-emerald-800 bg-emerald-50/60 hover:bg-emerald-100/80 transition-colors">COMPLETADO</button>
    </div>
</div>