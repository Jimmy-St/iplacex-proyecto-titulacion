<?php

use Livewire\Component;

new class extends Component
{
    public $picker;
};
?>


<div x-data="{ onColacion: false }" class="p-2 bg-slate-900 text-white text-xs">
    <!-- Prueba visual para verificar que el picker llegó -->
    {{-- <p class="text-green-400 mb-2">Picker recibido: {{ $picker->display_name }} (ID: {{ $picker->id }})</p> --}}

    <button @click="onColacion = !onColacion"
        :class="onColacion 
            ? 'bg-amber-500/20 border-amber-500/60 text-amber-300 shadow-[0_0_15px_rgba(245,158,11,0.25)]' 
            : 'bg-slate-800 border-white/[0.08] text-white/70 hover:text-white hover:bg-slate-700'"
        class="w-36 h-[34px] inline-flex items-center justify-center gap-2 px-3 border rounded-lg text-xs font-medium transition-all">

        <i data-lucide="coffee" class="w-3.5 h-3.5 shrink-0" :class="onColacion ? 'text-amber-400' : 'text-white/50'"></i>

        <span class="w-20 text-center truncate" x-text="onColacion ? 'En Colación' : 'A Colación'"></span>
    </button>
</div>