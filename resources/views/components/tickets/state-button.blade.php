@props(['ticket'])

<div class="relative">
    <button @click="if (status !== 'COMPLETADO') showStatusDropdown = !showStatusDropdown" 
            @click.away="showStatusDropdown = false"
            :disabled="status === 'COMPLETADO'"
            class="flex items-center justify-between w-36 px-3 py-1.5 rounded-lg border text-xs font-medium transition-colors"
            :class="{
                'bg-amber-500/10 border-amber-500/30 text-amber-400': status === 'PENDIENTE',
                'bg-blue-500/10 border-blue-500/30 text-blue-400': status === 'PREPARANDO',
                'bg-emerald-500/10 border-emerald-500/30 text-emerald-400 cursor-not-allowed': status === 'COMPLETADO'
            }">
        <div class="flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full"
                    :class="{
                        'bg-amber-400 animate-pulse': status === 'PENDIENTE',
                        'bg-blue-400 animate-pulse': status === 'PREPARANDO',
                        'bg-emerald-400': status === 'COMPLETADO'
                    }"></span>
            <span x-text="status"></span>
        </div>
        <i data-lucide="chevron-down" class="w-3.5 h-3.5 opacity-60" x-show="status !== 'COMPLETADO'" style="stroke-width:2"></i>
    </button>

    <div x-show="showStatusDropdown && status !== 'COMPLETADO'" 
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            class="absolute right-0 mt-2 w-36 rounded-xl bg-zinc-950 border border-white/[0.08] shadow-2xl p-1 z-50"
            style="display: none;">
        <button @click="changeStatus('PENDIENTE')" class="w-full text-left px-3 py-2 rounded-lg text-xs text-amber-400 hover:bg-white/[0.03] transition-colors">PENDIENTE</button>
        <button @click="changeStatus('PREPARANDO')" class="w-full text-left px-3 py-2 rounded-lg text-xs text-blue-400 hover:bg-white/[0.03] transition-colors">PREPARANDO</button>
        <button @click="changeStatus('COMPLETADO')" class="w-full text-left px-3 py-2 rounded-lg text-xs text-emerald-400 hover:bg-white/[0.03] transition-colors">COMPLETADO</button>
    </div>
</div>