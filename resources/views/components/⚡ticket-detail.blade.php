<?php

use App\Models\Ticket;
use App\Models\Picker;
use function Livewire\Volt\state;
use function Livewire\Volt\mount;
use function Livewire\Volt\computed;

state([
    'ticket' => fn (Ticket $ticket) => $ticket,
    'status' => fn (Ticket $ticket) => $ticket->status ?? 'PENDIENTE',
    'selectedPickers' => [],
    'assignedPickers' => [],
    'showStatusDropdown' => false,
    'showPickerModal' => false,
    'showPaymentAlert' => false,
    'showCompletionConfirm' => false,
    'isPaid' => true,
]);

mount(function (Ticket $ticket) {
    $this->ticket = $ticket;
    $this->status = $ticket->status ?? 'PENDIENTE';

    // Si el ticket ya tiene una tarea y operarios guardados, los cargamos
    if ($this->ticket->pickingTask) {
        $this->assignedPickers = $this->ticket->pickingTask->pickers->pluck('id')->toArray();
    }

    $this->selectedPickers = $this->assignedPickers;
});

$availablePickers = computed(function () {
    return Picker::orderBy('first_name', 'asc')->get();
});

$changeStatus = function ($newStatus) {
    if ($newStatus === 'COMPLETADO') {
        $this->showStatusDropdown = false;
        if (!$this->isPaid) {
            $this->showPaymentAlert = true;
        } else {
            $this->showCompletionConfirm = true;
        }
        return;
    }
    $this->status = $newStatus;
    $this->ticket->status = $newStatus;
    $this->ticket->save();
    $this->showStatusDropdown = false;
};

$executeCompletion = function () {
    $this->status = 'COMPLETADO';
    $this->ticket->status = 'COMPLETADO';
    $this->ticket->save();
    $this->showCompletionConfirm = false;
};

$addPickerToModal = function ($pickerId) {
    if (count($this->selectedPickers) < 5 && !in_array($pickerId, $this->selectedPickers)) {
        $this->selectedPickers[] = $pickerId;
    }
};

$removePickerFromModal = function ($pickerId) {
    $this->selectedPickers = array_values(array_filter($this->selectedPickers, fn($id) => $id != $pickerId));
};

$confirmSelection = function () {
    $task = $this->ticket->pickingTask()->firstOrCreate([
        'ticket_id' => $this->ticket->id
    ], [
        'status' => 'pending'
    ]);

    $task->pickers()->sync($this->selectedPickers);

    $count = count($this->selectedPickers);
    if ($count > 0) {
        $task->status = 'in_progress';
        $this->status = 'PREPARANDO';
    } else {
        $task->status = 'pending';
        $this->status = 'PENDIENTE';
    }

    $task->save();
    $this->ticket->status = $this->status;
    $this->ticket->save();

    $this->assignedPickers = $this->selectedPickers;
    $this->showPickerModal = false;
};

$getPickerObject = fn($id) => $this->availablePickers->firstWhere('id', $id);

?>

<div class="relative" x-data="{ showPickerModal: @entangle('showPickerModal') }">

  {{-- CAJA DE ICONOS OCULTOS --}}
  <div class="hidden">
      <div id="icono-eliminar">
          <i data-lucide="x" class="w-3.5 h-3.5" style="stroke-width:1.8"></i>
      </div>
  </div>

  @if($ticket)
    {{-- Botón Volver --}}
    <div class="mb-6 flex items-center gap-3">
      <a href="{{ route('tickets.index') }}"
         class="flex items-center gap-1.5 text-white/35 hover:text-white/70 text-sm transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4" style="stroke-width:1.5"></i>
        Volver
      </a>
      <span class="text-white/15">/</span>
      <span class="text-white/55 text-sm">Ticket #{{ $ticket->ticket_number }}</span>
    </div>

    {{-- LAYOUT SUPERIOR (2 Secciones) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-8">
        
        {{-- SECCIÓN 1: DATOS DEL TICKET --}}
        <div class="lg:col-span-2 bg-gray-900 border border-white/[0.06] rounded-xl p-5 flex flex-col justify-between gap-y-5">
            <div class="flex flex-wrap justify-between items-start gap-4">
                <div class="flex items-start gap-4">
                    <div>
                        <p class="text-[11px] text-white/35 uppercase tracking-widest mb-1">Documento de Venta</p>
                        <h1 class="text-xl font-semibold text-white tracking-tight">#{{ $ticket->ticket_number }}</h1>
                    </div>

                    {{-- Badge Tributario --}}
                    <div class="flex items-center gap-2 bg-white/[0.02] border border-white/[0.06] rounded-xl px-3 py-2 mt-1">
                        <i data-lucide="file-text" class="w-5 h-5 text-purple-400" style="stroke-width:1.5"></i>
                        <div class="leading-none">
                            <span class="text-xs font-medium text-white/80 block">{{ $ticket->document_type ?? 'Boleta' }}</span>
                            <span class="text-[10px] text-white/35 font-mono block mt-1">#{{ $ticket->document_number ?? '124850' }}</span>
                        </div>
                    </div>

                    {{-- Badge Estado de Pago --}}
                    <div class="mt-1">
                        <div x-show="isPaid" class="flex items-center gap-2 bg-emerald-500/5 border border-emerald-500/20 rounded-xl px-3 py-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            <span class="text-xs font-medium text-emerald-400">Pagado</span>
                        </div>
                    </div>
                </div>
                
                {{-- Dropdown de Estados --}}
                <div class="relative" x-data="{ openDropdown: false }">
                    <button @click="openDropdown = !openDropdown" 
                            @click.away="openDropdown = false"
                            class="flex items-center justify-between w-36 px-3 py-1.5 rounded-lg border text-xs font-medium transition-colors"
                            :class="{
                                'bg-amber-500/10 border-amber-500/30 text-amber-400': '{{ $status }}' === 'PENDIENTE',
                                'bg-blue-500/10 border-blue-500/30 text-blue-400': '{{ $status }}' === 'PREPARANDO',
                                'bg-emerald-500/10 border-emerald-500/30 text-emerald-400': '{{ $status }}' === 'COMPLETADO'
                            }">
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full"
                                  :class="{
                                      'bg-amber-400 animate-pulse': '{{ $status }}' === 'PENDIENTE',
                                      'bg-blue-400 animate-pulse': '{{ $status }}' === 'PREPARANDO',
                                      'bg-emerald-400': '{{ $status }}' === 'COMPLETADO'
                                  }"></span>
                            <span>{{ $status }}</span>
                        </div>
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 opacity-60" style="stroke-width:2"></i>
                    </button>

                    <div x-show="openDropdown" 
                         x-transition class="absolute right-0 mt-2 w-36 rounded-xl bg-zinc-950 border border-white/[0.08] shadow-2xl p-1 z-50"
                         style="display: none;">
                        <button wire:click="changeStatus('PENDIENTE')" @click="openDropdown = false" class="w-full text-left px-3 py-2 rounded-lg text-xs text-amber-400 hover:bg-white/[0.03]">Pendiente</button>
                        <button wire:click="changeStatus('PREPARANDO')" @click="openDropdown = false" class="w-full text-left px-3 py-2 rounded-lg text-xs text-blue-400 hover:bg-white/[0.03]">Preparando</button>
                        <button wire:click="changeStatus('COMPLETADO')" @click="openDropdown = false" class="w-full text-left px-3 py-2 rounded-lg text-xs text-emerald-400 hover:bg-white/[0.03]">Completado</button>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-3 border-t border-white/[0.04]">
                <div class="flex items-center gap-8">
                    <div>
                        <p class="text-[10px] text-white/35 uppercase tracking-widest mb-0.5">Monto Total</p>
                        <p class="text-base font-semibold text-white/90">${{ number_format($ticket->total_amount, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-white/35 uppercase tracking-widest mb-0.5">Vendedor (Seller)</p>
                        <p class="text-sm text-white/70 max-w-[150px] truncate">{{ $ticket->seller ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-white/35 uppercase tracking-widest mb-0.5">Canal de Origen</p>
                        <p class="text-sm text-white/70">Venta Digital</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-[10px] text-white/35 uppercase tracking-widest mb-0.5">Actualizado</p>
                    <p class="text-sm text-white/55 font-mono">{{ $ticket->updated_at ? $ticket->updated_at->format('d-m-Y H:i') : '—' }}</p>
                </div>
            </div>
        </div>

        {{-- SECCIÓN 2: ASIGNACIÓN DE PICKERS --}}
        <div class="bg-gray-900 border border-white/[0.06] rounded-xl p-5 flex flex-col justify-between min-h-[140px]">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <p class="text-[11px] text-white/35 uppercase tracking-widest mb-1">Pickers Asignados</p>
                    <p class="text-xs font-medium text-white/80">{{ count($assignedPickers) }} / 5 Operadores</p>
                </div>
                
                <button wire:click="$set('showPickerModal', true)" 
                        class="p-2 rounded-xl border transition-all flex items-center justify-center {{ count($assignedPickers) === 0 ? 'bg-white/[0.02] border-white/[0.06] text-white/20' : 'bg-purple-500/10 border-purple-500/30 text-purple-400' }}">
                    <i data-lucide="user" class="w-5 h-5" style="stroke-width:1.5"></i>
                </button>
            </div>

            <div class="flex flex-wrap gap-1.5 mt-2">
                @forelse($assignedPickers as $pickerId)
                    @php $p = $this->getPickerObject($pickerId); @endphp
                    @if($p)
                        <span class="inline-flex items-center bg-white/[0.04] border border-white/[0.06] rounded-lg px-2.5 py-0.5 text-[11px] text-white/70 max-w-[140px]">
                            <span class="truncate whitespace-nowrap">{{ $p->display_name }}</span>
                        </span>
                    @endif
                @empty
                    <span class="text-xs text-white/25 italic py-1">Sin personal asignado</span>
                @endforelse
            </div>
        </div>

    </div>

    {{-- MODAL ASIGNAR PICKERS --}}
    <div x-show="showPickerModal" class="fixed inset-0 z-50 flex justify-end" style="display: none;" role="dialog">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="$wire.set('showPickerModal', false)"></div>
        
        <div class="relative w-full max-w-md h-full bg-zinc-950 border-l border-white/[0.08] shadow-2xl flex flex-col text-white z-50 overflow-hidden">
            
            <div class="p-4 border-b border-white/[0.06] flex items-center justify-between shrink-0">
                <div>
                    <h3 class="text-sm font-semibold">Asignación de Personal</h3>
                    <p class="text-[11px] text-white/40 mt-0.5">{{ count($selectedPickers) }} de 5 seleccionados</p>
                </div>
                <button wire:click="$set('showPickerModal', false)" class="p-1 rounded-lg text-white/40 hover:text-white/80"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>

            <div class="flex-1 overflow-y-auto p-4 flex flex-col md:flex-row gap-4 custom-scrollbar">
                
                {{-- Disponibles --}}
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] text-white/35 uppercase tracking-widest font-semibold mb-2 sticky top-0 bg-zinc-950 py-1 z-10">Disponibles</p>
                    <div class="space-y-1.5">
                        @foreach($this->availablePickers as $picker)
                            @php $isSelected = in_array($picker->id, $selectedPickers); @endphp
                            <button wire:click="addPickerToModal({{ $picker->id }})" 
                                    @if($isSelected) disabled @endif
                                    class="w-full flex items-center justify-between text-left p-2.5 rounded-xl border text-xs transition-all gap-3 min-w-0 {{ $isSelected ? 'bg-zinc-900 border-white/[0.02] text-white/20 cursor-not-allowed' : 'bg-white/[0.02] border-white/[0.05] hover:border-white/[0.15] text-white/80' }}">
                                <span class="truncate whitespace-nowrap flex-1 min-w-0">{{ $picker->display_name }}</span>
                                <span class="flex items-center justify-center shrink-0 w-5 h-5 rounded-full text-[10px] font-bold font-mono bg-white/[0.03] text-white/40 border border-white/[0.06]">0</span>
                            </button>
                        @endforeach
                    </div>
                </div>
                
                {{-- Selección --}}
                <div class="w-full md:w-44 bg-white/[0.01] border border-white/[0.04] rounded-xl p-3 flex flex-col h-fit md:sticky md:top-0 min-w-0">
                    <p class="text-[10px] text-white/35 uppercase tracking-widest font-semibold mb-2">Selección</p>
                    <div class="space-y-1.5 min-w-0">
                        @forelse($selectedPickers as $pickerId)
                            @php $p = $this->getPickerObject($pickerId); @endphp
                            @if($p)
                                <div class="flex items-center justify-between bg-purple-500/10 border border-purple-500/20 text-purple-300 rounded-lg p-2 text-xs gap-2 min-w-0">
                                    <span class="truncate whitespace-nowrap flex-1 min-w-0">{{ $p->display_name }}</span>
                                    <button wire:click="removePickerFromModal({{ $pickerId }})" class="text-purple-400 hover:text-white p-0.5 shrink-0">
                                        <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                    </button>
                                </div>
                            @endif
                        @empty
                            <p class="text-[11px] text-white/20 italic text-center py-4">Ninguno</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="p-4 border-t border-white/[0.06] bg-zinc-900/40 shrink-0">
                <button wire:click="confirmSelection" class="w-full bg-white text-zinc-950 font-medium py-2.5 rounded-xl text-xs hover:bg-white/90 shadow-lg">Confirmar Selección</button>
            </div>
        </div>
    </div>

    {{-- MODAL PAGO PENDIENTE --}}
    <div x-show="@entangle('showPaymentAlert')" class="fixed inset-0 z-[100] flex items-center justify-center p-4" style="display: none;">
        <div class="fixed inset-0 bg-black/70 backdrop-blur-md" @click="$wire.set('showPaymentAlert', false)"></div>
        <div class="relative w-full max-w-sm bg-zinc-900 border border-white/[0.08] rounded-2xl p-6 text-center shadow-2xl z-50">
            <h3 class="text-sm font-semibold text-white">Acción Bloqueada</h3>
            <p class="text-xs text-white/50 mt-2">El Ticket aún no ha sido pagado.</p>
            <button wire:click="$set('showPaymentAlert', false)" class="mt-5 w-full bg-white/[0.04] text-white/80 py-2 rounded-xl text-xs">Entendido</button>
        </div>
    </div>

    {{-- MODAL CONFIRMACIÓN COMPLETAR --}}
    <div x-show="@entangle('showCompletionConfirm')" class="fixed inset-0 z-[100] flex items-center justify-center p-4" style="display: none;">
        <div class="fixed inset-0 bg-black/70 backdrop-blur-md" @click="$wire.set('showCompletionConfirm', false)"></div>
        <div class="relative w-full max-w-sm bg-zinc-900 border border-white/[0.08] rounded-2xl p-6 text-center shadow-2xl z-50">
            <h3 class="text-sm font-semibold text-white">¿Cerrar Preparación de Ticket?</h3>
            <div class="grid grid-cols-2 gap-3 mt-6">
                <button wire:click="$set('showCompletionConfirm', false)" class="bg-white/[0.02] text-white/60 py-2.5 rounded-xl text-xs">Aún no</button>
                <button wire:click="executeCompletion" class="bg-emerald-500 text-zinc-950 font-semibold py-2.5 rounded-xl text-xs">Sí, Completar</button>
            </div>
        </div>
    </div>

    <x-tickets.product-grid :items="$ticket->items" />

  @else
    <div class="flex flex-col items-center justify-center min-h-[45vh] text-center border border-dashed border-white/[0.06] rounded-xl p-8 bg-gray-900/20">
      <h2 class="text-base font-medium text-white/80">Ticket no existe</h2>
    </div>
  @endif

  <style>
      .custom-scrollbar::-webkit-scrollbar { width: 4px; }
      .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
      .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.05); border-radius: 10px; }
  </style>
</div>