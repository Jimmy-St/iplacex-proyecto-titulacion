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
             class="flex items-center gap-1.5 text-slate-500 hover:text-slate-900 text-sm font-medium transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4" style="stroke-width:1.75"></i>
            Volver
          </a>
          <span class="text-slate-300">/</span>
          <span class="text-slate-700 text-sm font-medium">Ticket #{{ $ticket->ticket_number }}</span>
        </div>

        {{-- LAYOUT SUPERIOR (2 Secciones) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-8">

            {{-- SECCIÓN 1: DATOS DEL TICKET --}}
            <div class="lg:col-span-2 bg-white border border-purple-200 rounded-xl p-5 flex flex-col justify-between gap-y-5 shadow-sm">
                <div class="flex flex-wrap justify-between items-start gap-4">
                    <div class="flex items-start gap-4">
                        <div>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Número de Ticket</p>
                            <h1 class="text-xl font-bold text-slate-900 tracking-tight">#{{ $ticket->ticket_number }}</h1>
                        </div>
                    </div>

                    {{-- Dropdown de Estados --}}
                    <x-tickets.state-button :ticket="$ticket" />
                </div>

                {{-- Detalles Inferiores Originales --}}
                <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                    <div class="flex items-center gap-8">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Monto Total</p>
                            <p class="text-base font-bold text-slate-900">${{ number_format($ticket->total_amount, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Vendedor (Seller)</p>
                            <p class="text-sm font-medium text-slate-700 max-w-[150px] truncate">{{ $ticket->seller ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Canal de Origen</p>
                            <p class="text-sm font-medium text-slate-700">Venta Digital</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Actualizado</p>
                        <p class="text-sm text-slate-600 font-mono font-medium">{{ $ticket->updated_at ? $ticket->updated_at->format('d-m-Y H:i') : '—' }}</p>
                    </div>
                </div>
            </div>

            {{-- SECCIÓN 2: ASIGNACIÓN DE PICKERS --}}
            <div class="bg-white border border-purple-200 rounded-xl p-5 flex flex-col justify-between min-h-[140px] shadow-sm">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Pickers Asignados</p>
                        <p class="text-xs font-semibold text-slate-700" x-text="assignedPickers.length + ' / 5 Operadores'"></p>
                    </div>

                    <button @click="openModal()" 
                            :disabled="status === 'COMPLETADO'"
                            class="p-2 rounded-xl border transition-all flex items-center justify-center shadow-sm"
                            :class="status === 'COMPLETADO' ? 'bg-slate-50 border-slate-200 text-slate-300 cursor-not-allowed' : (assignedPickers.length === 0 ? 'bg-slate-50 border-purple-200 text-slate-400 hover:text-purple-600' : 'bg-purple-50 border-purple-300 text-purple-700')">
                        <i data-lucide="user" x-show="assignedPickers.length === 0" class="w-5 h-5" style="stroke-width:1.75"></i>
                        <i data-lucide="user-check" x-show="assignedPickers.length === 1" class="w-5 h-5" style="stroke-width:1.75" style="display: none;"></i>
                        <i data-lucide="users" x-show="assignedPickers.length > 1" class="w-5 h-5" style="stroke-width:1.75" style="display: none;"></i>
                    </button>
                </div>

                {{-- Tags Exteriores --}}
                <div class="flex flex-wrap gap-1.5 mt-2">
                    <template x-if="assignedPickers.length === 0">
                        <span class="text-xs text-slate-400 italic py-1 font-medium">Sin personal asignado</span>
                    </template>
                    <template x-for="p in assignedPickers" :key="p.id">
                        <span class="inline-flex items-center bg-purple-50 border border-purple-200 rounded-lg px-2.5 py-0.5 text-[11px] font-semibold text-purple-800 max-w-[140px]">
                            <span class="truncate whitespace-nowrap" x-text="p.display_name"></span>
                        </span>
                    </template>
                </div>
            </div>

        </div>

        {{-- MODAL ASIGNAR PICKERS --}}
        <div x-show="showPickerModal" class="fixed inset-0 z-50 flex justify-end" style="display: none;" role="dialog" aria-modal="true">
            <div x-show="showPickerModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showPickerModal = false"></div>

            <div x-show="showPickerModal" 
                 x-transition:enter="transition ease-out duration-300 transform" 
                 x-transition:enter-start="translate-x-full" 
                 x-transition:enter-end="translate-x-0" 
                 class="relative w-full max-w-md h-full bg-white border-l border-purple-200 shadow-2xl flex flex-col text-slate-900 z-50 pb-20 md:pb-0 overflow-hidden">

                {{-- Cabecera Fija --}}
                <div class="p-4 border-b border-purple-100 flex items-center justify-between shrink-0 bg-slate-50/50">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Asignación de Personal</h3>
                        <p class="text-[11px] font-medium text-slate-500 mt-0.5" x-text="modalPickers.length + ' de 5 seleccionados'"></p>
                    </div>
                    <button @click="showPickerModal = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100"><i data-lucide="x" class="w-5 h-5"></i></button>
                </div>

                {{-- Cuerpo del Modal con Scroll Único --}}
                <div class="flex-1 overflow-y-auto p-4 flex flex-col md:flex-row gap-4 mb-6 md:mb-0 custom-scrollbar">

                    {{-- LADO IZQUIERDO: Disponibles --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold mb-2 sticky top-0 bg-white py-1 z-10">Disponibles</p>
                        <div class="space-y-1.5">
                            <template x-for="picker in availablePickers" :key="picker.id">
                                <button @click="addPickerToModal(picker)" 
                                        class="w-full flex items-center justify-between text-left p-2.5 rounded-xl border text-xs transition-all gap-3 min-w-0 shadow-sm" 
                                        :class="modalPickers.find(p => p.id === picker.id) ? 'bg-slate-100 border-slate-200 text-slate-400 cursor-not-allowed' : 'bg-white border-purple-200 hover:border-purple-400 text-slate-800 hover:bg-purple-50/30 font-medium'">

                                    <span class="truncate whitespace-nowrap flex-1 min-w-0 font-semibold" x-text="picker.display_name"></span>

                                    {{-- BADGE DINÁMICO DE TAREAS ACTIVAS --}}
                                    <span class="flex items-center justify-center shrink-0 px-1.5 h-5 min-w-[20px] rounded-full text-[10px] font-bold font-mono tracking-tighter border transition-colors shadow-sm"
                                          :class="picker.active_tasks_count > 0 
                                              ? 'bg-rose-50 border-rose-200 text-rose-700' 
                                              : 'bg-emerald-50 border-emerald-200 text-emerald-700'"
                                          x-text="picker.active_tasks_count">
                                    </span>

                                </button>
                            </template>
                        </div>
                    </div>

                    {{-- LADO DERECHO: Selección en Modal --}}
                    <div class="w-full md:w-44 bg-slate-50 border border-purple-200 rounded-xl p-3 flex flex-col h-fit md:sticky md:top-0 min-w-0 shadow-sm">
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold mb-2">Selección</p>
                        <div class="space-y-1.5 min-w-0">
                            <template x-for="p in modalPickers" :key="p.id">
                                <div class="flex items-center justify-between bg-purple-50 border border-purple-200 text-purple-900 rounded-lg p-2 text-xs gap-2 min-w-0 shadow-sm">
                                    <span class="truncate whitespace-nowrap flex-1 min-w-0 font-semibold" x-text="p.display_name"></span>
                                    <button @click="removePickerFromModal(p.id)" class="text-purple-600 hover:text-purple-900 transition-colors p-0.5 flex items-center justify-center shrink-0">
                                        <span x-html="document.getElementById('icono-eliminar').innerHTML"></span>
                                    </button>
                                </div>
                            </template>
                            <template x-if="modalPickers.length === 0">
                                <p class="text-[11px] text-slate-400 font-medium italic text-center py-4">Ninguno</p>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Footer Fijo --}}
                <div class="p-4 border-t border-purple-100 bg-slate-50/50 shrink-0">
                    <button @click="confirmSelection()" class="w-full bg-purple-600 text-white font-semibold py-2.5 rounded-xl text-xs hover:bg-purple-700 transition-colors shadow-md shadow-purple-600/20 cursor-pointer">Confirmar Selección</button>
                </div>
            </div>
        </div>

        {{-- MODAL 1: AVISO PAGO PENDIENTE --}}
        <div x-show="showPaymentAlert" class="fixed inset-0 z-[100] flex items-center justify-center p-4" style="display: none;" role="dialog" aria-modal="true">
            <div x-show="showPaymentAlert" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showPaymentAlert = false"></div>

            <div x-show="showPaymentAlert" 
                 x-transition:enter="transition ease-out duration-200 transform" 
                 x-transition:enter-start="opacity-0 scale-95" 
                 x-transition:enter-end="opacity-100 scale-100" 
                 class="relative w-full max-w-sm bg-white border border-purple-200 rounded-2xl p-6 text-center shadow-2xl z-50">

                <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-amber-50 border border-amber-200 text-amber-700 mb-4 shadow-sm">
                    <i data-lucide="shield-alert" class="w-7 h-7" style="stroke-width:1.75"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-900 tracking-tight">Acción Bloqueada</h3>
                <p class="text-xs font-medium text-slate-600 mt-2 leading-relaxed">
                    El Ticket aún no ha sido pagado, por favor revisar de forma interna antes de completar el pedido.
                </p>
                <div class="mt-5">
                    <button @click="showPaymentAlert = false" class="w-full bg-slate-100 border border-slate-200 text-slate-700 hover:text-slate-900 hover:bg-slate-200 font-semibold py-2 rounded-xl text-xs transition-colors shadow-sm">Entendido, revisar</button>
                </div>
            </div>
        </div>

        {{-- MODAL 2: CONFIRMACIÓN PASO CRÍTICO A COMPLETADO --}}
        <div x-show="showCompletionConfirm" class="fixed inset-0 z-[100] flex items-center justify-center p-4" style="display: none;" role="dialog" aria-modal="true">
            <div x-show="showCompletionConfirm" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showCompletionConfirm = false"></div>

            <div x-show="showCompletionConfirm" 
                 x-transition:enter="transition ease-out duration-200 transform" 
                 x-transition:enter-start="opacity-0 scale-95" 
                 x-transition:enter-end="opacity-100 scale-100" 
                 class="relative w-full max-w-sm bg-white border border-purple-200 rounded-2xl p-6 text-center shadow-2xl z-50">

                <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-purple-50 border border-purple-200 text-purple-700 mb-4 shadow-sm">
                    <i data-lucide="check-circle-2" class="w-7 h-7" style="stroke-width:1.75"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-900 tracking-tight">¿Cerrar Preparación de Ticket?</h3>
                <p class="text-xs font-medium text-slate-600 mt-2 leading-relaxed">
                    Estás a punto de marcar este pedido como <span class="text-emerald-600 font-bold">COMPLETADO</span>. Esto liberará la dotación de pickers asignada y cerrará el flujo. ¿Proceder?
                </p>
                <div class="grid grid-cols-2 gap-3 mt-6">
                    <button @click="showCompletionConfirm = false" class="w-full bg-slate-100 border border-slate-200 text-slate-700 hover:text-slate-900 hover:bg-slate-200 font-semibold py-2.5 rounded-xl text-xs transition-colors shadow-sm">Aún no</button>
                    <button @click="executeCompletion()" class="w-full bg-emerald-600 text-white hover:bg-emerald-700 font-semibold py-2.5 rounded-xl text-xs transition-colors shadow-md shadow-emerald-600/20 cursor-pointer">Sí, Completar</button>
                </div>
            </div>
        </div>

    </div>

    {{-- GRILLA DE PRODUCTOS --}}
    <x-tickets.product-grid :items="$ticket->items" />

  @else
    {{-- VISTA ERROR CONTROLADO --}}
    <div class="mb-6 flex items-center gap-3">
      <a href="{{ route('tickets.index') }}" class="flex items-center gap-1.5 text-slate-500 hover:text-slate-900 text-sm font-medium transition-colors"><i data-lucide="arrow-left" class="w-4 h-4" style="stroke-width:1.75"></i>Volver al listado</a>
    </div>
    <div class="flex flex-col items-center justify-center min-h-[45vh] text-center border border-dashed border-purple-200 rounded-xl p-8 bg-white shadow-sm">
      <i data-lucide="frown" class="w-12 h-12 text-slate-400 mb-4" style="stroke-width:1.5"></i>
      <h2 class="text-base font-bold text-slate-800">Ticket no existe</h2>
    </div>
  @endif

  <style>
      .custom-scrollbar::-webkit-scrollbar { width: 4px; }
      .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
      .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(168, 85, 247, 0.2); border-radius: 10px; }
      .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(168, 85, 247, 0.4); }
  </style>

@endsection