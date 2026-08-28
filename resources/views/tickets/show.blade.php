@extends('layouts.app')

@section('content')

  {{-- ========================================================================= --}}
  {{-- CAJA DE ICONOS OCULTOS: Lucide los renderiza aquí en el arranque inicial    --}}
  {{-- ========================================================================= --}}
  <div class="hidden">
      <div id="icono-eliminar">
          <i data-lucide="x" class="w-3.5 h-3.5" style="stroke-width:1.8"></i>
      </div>
  </div>

  @if($ticket)
    {{-- ========================================================================= --}}
    {{-- VISTA DETALLE: Se renderiza si el Ticket SÍ existe                         --}}
    {{-- ========================================================================= --}}
    <script>
        window.availablePickersData = @json($pickers);
        window.assignedPickersData = @json($ticket->pickingTask && $ticket->pickingTask->pickers ? $ticket->pickingTask->pickers : []);
        window.csrfToken = @json(csrf_token());
    </script>

    {{-- Contenedor Maestro Interactivo con Alpine.js --}}
    <div x-data="{ 
        status: '{{ optional($ticket->pickingTask)->status ?? 'PENDIENTE' }}',
        showStatusDropdown: false,
        showPickerModal: false,
        showPaymentAlert: false,    
        showCompletionConfirm: false, 

        isPaid: true, 

        availablePickers: window.availablePickersData || [],
        assignedPickers: window.assignedPickersData || [], 
        modalPickers: [],    

        changeStatus(newStatus) {
            // Candado: Si ya está completado, no permitimos cambiar de estado
            if (this.status === 'COMPLETADO') return;

            if (newStatus === 'COMPLETADO') {
                this.showStatusDropdown = false;
                if (!this.isPaid) {
                    this.showPaymentAlert = true; 
                } else {
                    this.showCompletionConfirm = true; 
                }
                return;
            }
            this.status = newStatus;
            this.showStatusDropdown = false;
        },

        executeCompletion() {
            this.status = 'COMPLETADO';
            this.showCompletionConfirm = false;

            const payload = {
                ticket_id: {{ $ticket->id }}
            };

            fetch('/ticket/complete-ticket', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Ticket completado en BD:', data);
                    this.status = data.status_tarea;
                }
            })
            .catch(error => {
                console.error('Error al completar el ticket:', error);
            });
        },

        openModal() {
            // Candado: Si está COMPLETADO, bloqueamos la apertura del modal de pickers
            if (this.status === 'COMPLETADO') return;

            this.modalPickers = [...this.assignedPickers];
            this.showPickerModal = true;
        },
        confirmSelection() {
            this.assignedPickers = [...this.modalPickers];
            this.showPickerModal = false;

            if (this.assignedPickers.length > 0 && this.status === 'PENDIENTE') {
                this.status = 'PREPARANDO';
            } 
            else if (this.assignedPickers.length === 0 && this.status === 'PREPARANDO') {
                this.status = 'PENDIENTE';
            }

            const payload = {
                ticket_id: {{ $ticket->id }},
                pickers: this.assignedPickers.map(picker => picker.id),
                status: this.status
            };

            fetch('/ticket/update-pickers', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Sincronizado correctamente en BD:', data);
                    this.status = data.status_tarea;
                }
            })
            .catch(error => {
                console.error('Error al actualizar pickers:', error);
            });
        },

        addPickerToModal(picker) {
            if (this.modalPickers.length < 5 && !this.modalPickers.find(p => p.id === picker.id)) {
                this.modalPickers.push(picker);
            }
        },
        removePickerFromModal(id) {
            this.modalPickers = this.modalPickers.filter(p => p.id !== id);
        }
    }" class="relative">

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
                            <p class="text-[11px] text-white/35 uppercase tracking-widest mb-1">Número de Ticket</p>
                            <h1 class="text-xl font-semibold text-white tracking-tight">#{{ $ticket->ticket_number }}</h1>
                        </div>

                        {{-- Badge Boleta --}}
                        <x-tickets.receipt-badge :ticket="$ticket" />

                        {{-- Badge Estado de Pago --}}
                        <x-tickets.paid-badge :ticket="$ticket" />
                    </div>

                    {{-- Dropdown de Estados --}}
                    <x-tickets.state-button :ticket="$ticket" />
                </div>

                {{-- Detalles Inferiores Originales --}}
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
                        <p class="text-xs font-medium text-white/80" x-text="assignedPickers.length + ' / 5 Operadores'"></p>
                    </div>

                    <button @click="openModal()" 
                            :disabled="status === 'COMPLETADO'"
                            class="p-2 rounded-xl border transition-all flex items-center justify-center"
                            :class="status === 'COMPLETADO' ? 'bg-white/[0.02] border-white/[0.04] text-white/15 cursor-not-allowed' : (assignedPickers.length === 0 ? 'bg-white/[0.02] border-white/[0.06] text-white/20' : 'bg-purple-500/10 border-purple-500/30 text-purple-400')">
                        <i data-lucide="user" x-show="assignedPickers.length === 0" class="w-5 h-5" style="stroke-width:1.5"></i>
                        <i data-lucide="user-check" x-show="assignedPickers.length === 1" class="w-5 h-5" style="stroke-width:1.5" style="display: none;"></i>
                        <i data-lucide="users" x-show="assignedPickers.length > 1" class="w-5 h-5" style="stroke-width:1.5" style="display: none;"></i>
                    </button>
                </div>

                {{-- Tags Exteriores --}}
                <div class="flex flex-wrap gap-1.5 mt-2">
                    <template x-if="assignedPickers.length === 0">
                        <span class="text-xs text-white/25 italic py-1">Sin personal asignado</span>
                    </template>
                    <template x-for="p in assignedPickers" :key="p.id">
                        <span class="inline-flex items-center bg-white/[0.04] border border-white/[0.06] rounded-lg px-2.5 py-0.5 text-[11px] text-white/70 max-w-[140px]">
                            <span class="truncate whitespace-nowrap" x-text="p.display_name"></span>
                        </span>
                    </template>
                </div>
            </div>

        </div>

        {{-- MODAL ASIGNAR PICKERS --}}
        <div x-show="showPickerModal" class="fixed inset-0 z-50 flex justify-end" style="display: none;" role="dialog" aria-modal="true">
            <div x-show="showPickerModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="showPickerModal = false"></div>

            <div x-show="showPickerModal" 
                 x-transition:enter="transition ease-out duration-300 transform" 
                 x-transition:enter-start="translate-x-full" 
                 x-transition:enter-end="translate-x-0" 
                 class="relative w-full max-w-md h-full bg-zinc-950 border-l border-white/[0.08] shadow-2xl flex flex-col text-white z-50 pb-20 md:pb-0 overflow-hidden">

                {{-- Cabecera Fija --}}
                <div class="p-4 border-b border-white/[0.06] flex items-center justify-between shrink-0">
                    <div>
                        <h3 class="text-sm font-semibold">Asignación de Personal</h3>
                        <p class="text-[11px] text-white/40 mt-0.5" x-text="modalPickers.length + ' de 5 seleccionados'"></p>
                    </div>
                    <button @click="showPickerModal = false" class="p-1 rounded-lg text-white/40 hover:text-white/80 hover:bg-white/[0.04]"><i data-lucide="x" class="w-5 h-5"></i></button>
                </div>

                {{-- Cuerpo del Modal con Scroll Único --}}
                <div class="flex-1 overflow-y-auto p-4 flex flex-col md:flex-row gap-4 mb-6 md:mb-0 custom-scrollbar">

                    {{-- LADO IZQUIERDO: Disponibles --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-[10px] text-white/35 uppercase tracking-widest font-semibold mb-2 sticky top-0 bg-zinc-950 py-1 z-10">Disponibles</p>
                        <div class="space-y-1.5">
                            <template x-for="picker in availablePickers" :key="picker.id">
                                <button @click="addPickerToModal(picker)" 
                                        class="w-full flex items-center justify-between text-left p-2.5 rounded-xl border text-xs transition-all gap-3 min-w-0" 
                                        :class="modalPickers.find(p => p.id === picker.id) ? 'bg-zinc-900 border-white/[0.02] text-white/20 cursor-not-allowed' : 'bg-white/[0.02] border-white/[0.05] hover:border-white/[0.15] text-white/80 hover:bg-white/[0.04]'">

                                    <span class="truncate whitespace-nowrap flex-1 min-w-0" x-text="picker.display_name"></span>

                                    {{-- BADGE DINÁMICO DE TAREAS ACTIVAS (Verde si es 0, Rojo si es >= 1) --}}
                                    <span class="flex items-center justify-center shrink-0 px-1.5 h-5 min-w-[20px] rounded-full text-[10px] font-bold font-mono tracking-tighter border transition-colors"
                                          :class="picker.active_tasks_count > 0 
                                              ? 'bg-rose-500/10 border-rose-500/30 text-rose-400' 
                                              : 'bg-emerald-500/10 border-emerald-500/30 text-emerald-400'"
                                          x-text="picker.active_tasks_count">
                                    </span>

                                </button>
                            </template>
                        </div>
                    </div>

                    {{-- LADO DERECHO: Selección en Modal --}}
                    <div class="w-full md:w-44 bg-white/[0.01] border border-white/[0.04] rounded-xl p-3 flex flex-col h-fit md:sticky md:top-0 min-w-0">
                        <p class="text-[10px] text-white/35 uppercase tracking-widest font-semibold mb-2">Selección</p>
                        <div class="space-y-1.5 min-w-0">
                            <template x-for="p in modalPickers" :key="p.id">
                                <div class="flex items-center justify-between bg-purple-500/10 border border-purple-500/20 text-purple-300 rounded-lg p-2 text-xs gap-2 min-w-0">
                                    <span class="truncate whitespace-nowrap flex-1 min-w-0" x-text="p.display_name"></span>
                                    <button @click="removePickerFromModal(p.id)" class="text-purple-400 hover:text-white transition-colors p-0.5 flex items-center justify-center shrink-0">
                                        <span x-html="document.getElementById('icono-eliminar').innerHTML"></span>
                                    </button>
                                </div>
                            </template>
                            <template x-if="modalPickers.length === 0">
                                <p class="text-[11px] text-white/20 italic text-center py-4">Ninguno</p>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Footer Fijo --}}
                <div class="p-4 border-t border-white/[0.06] bg-zinc-900/40 shrink-0">
                    <button @click="confirmSelection()" class="w-full bg-white text-zinc-950 font-medium py-2.5 rounded-xl text-xs hover:bg-white/90 transition-colors shadow-lg">Confirmar Selección</button>
                </div>
            </div>
        </div>

        {{-- MODAL 1: AVISO PAGO PENDIENTE --}}
        <div x-show="showPaymentAlert" class="fixed inset-0 z-[100] flex items-center justify-center p-4" style="display: none;" role="dialog" aria-modal="true">
            <div x-show="showPaymentAlert" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-black/70 backdrop-blur-md" @click="showPaymentAlert = false"></div>

            <div x-show="showPaymentAlert" 
                 x-transition:enter="transition ease-out duration-200 transform" 
                 x-transition:enter-start="opacity-0 scale-95" 
                 x-transition:enter-end="opacity-100 scale-100" 
                 class="relative w-full max-w-sm bg-zinc-900 border border-white/[0.08] rounded-2xl p-6 text-center shadow-2xl z-50">

                <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-400 mb-4">
                    <i data-lucide="shield-alert" class="w-7 h-7" style="stroke-width:1.5"></i>
                </div>
                <h3 class="text-sm font-semibold text-white tracking-tight">Acción Bloqueada</h3>
                <p class="text-xs text-white/50 mt-2 leading-relaxed">
                    El Ticket aún no ha sido pagado, por favor revisar de forma interna antes de completar el pedido.
                </p>
                <div class="mt-5">
                    <button @click="showPaymentAlert = false" class="w-full bg-white/[0.04] border border-white/[0.08] text-white/80 hover:text-white hover:bg-white/[0.08] font-medium py-2 rounded-xl text-xs transition-colors">Entendido, revisar</button>
                </div>
            </div>
        </div>

        {{-- MODAL 2: CONFIRMACIÓN PASO CRÍTICO A COMPLETADO --}}
        <div x-show="showCompletionConfirm" class="fixed inset-0 z-[100] flex items-center justify-center p-4" style="display: none;" role="dialog" aria-modal="true">
            <div x-show="showCompletionConfirm" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-black/70 backdrop-blur-md" @click="showCompletionConfirm = false"></div>

            <div x-show="showCompletionConfirm" 
                 x-transition:enter="transition ease-out duration-200 transform" 
                 x-transition:enter-start="opacity-0 scale-95" 
                 x-transition:enter-end="opacity-100 scale-100" 
                 class="relative w-full max-w-sm bg-zinc-900 border border-white/[0.08] rounded-2xl p-6 text-center shadow-2xl z-50">

                <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-purple-500/10 border border-purple-500/20 text-purple-400 mb-4">
                    <i data-lucide="check-circle-2" class="w-7 h-7" style="stroke-width:1.5"></i>
                </div>
                <h3 class="text-sm font-semibold text-white tracking-tight">¿Cerrar Preparación de Ticket?</h3>
                <p class="text-xs text-white/50 mt-2 leading-relaxed">
                    Estás a punto de marcar este pedido como <span class="text-emerald-400 font-medium">COMPLETADO</span>. Esto liberará la dotación de pickers asignada y cerrará el flujo. ¿Proceder?
                </p>
                <div class="grid grid-cols-2 gap-3 mt-6">
                    <button @click="showCompletionConfirm = false" class="w-full bg-white/[0.02] border border-white/[0.06] text-white/60 hover:text-white hover:bg-white/[0.04] font-medium py-2.5 rounded-xl text-xs transition-colors">Aún no</button>
                    <button @click="executeCompletion()" class="w-full bg-emerald-500 text-zinc-950 hover:bg-emerald-400 font-semibold py-2.5 rounded-xl text-xs transition-colors shadow-lg shadow-emerald-500/10">Sí, Completar</button>
                </div>
            </div>
        </div>

    </div>

    {{-- GRILLA DE PRODUCTOS --}}
    <x-tickets.product-grid :items="$ticket->items" />

  @else
    {{-- VISTA ERROR CONTROLADO --}}
    <div class="mb-6 flex items-center gap-3">
      <a href="{{ route('tickets.index') }}" class="flex items-center gap-1.5 text-white/35 hover:text-white/70 text-sm transition-colors"><i data-lucide="arrow-left" class="w-4 h-4" style="stroke-width:1.5"></i>Volver al listado</a>
    </div>
    <div class="flex flex-col items-center justify-center min-h-[45vh] text-center border border-dashed border-white/[0.06] rounded-xl p-8 bg-gray-900/20">
      <i data-lucide="frown" class="w-12 h-12 text-white/20 mb-4" style="stroke-width:1.2"></i>
      <h2 class="text-base font-medium text-white/80">Ticket no existe</h2>
    </div>
  @endif

  <style>
      .custom-scrollbar::-webkit-scrollbar { width: 4px; }
      .custom-scrollbar::-webkit-scrollbar-track { bg: transparent; }
      .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.05); border-radius: 10px; }
      .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.1); }
  </style>

@endsection