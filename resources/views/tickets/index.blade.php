@extends('layouts.app')

@section('content')

  <div class="mb-6">
    <span class="text-xs font-bold text-slate-600 uppercase tracking-widest block mb-2">Tickets del día</span>
    
    {{-- Barra de Filtros (Fecha, Búsqueda y Estados) --}}
    <div class="flex flex-wrap items-center gap-2">

      {{-- Filtro fecha — submit automático al cambiar --}}
      <form method="GET" action="{{ route('tickets.index') }}">
        @if(request('buscar'))
          <input type="hidden" name="buscar" value="{{ request('buscar') }}">
        @endif
        @if(request('estado'))
          <input type="hidden" name="estado" value="{{ request('estado') }}">
        @endif
        <input
          type="date"
          name="fecha"
          value="{{ $fecha }}"
          onchange="this.form.submit()"
          onclick="this.showPicker()"
          class="bg-white border border-purple-200 rounded-lg px-3 h-9
                 text-slate-900 font-semibold text-base outline-none
                 focus:border-purple-600 cursor-pointer transition-colors shadow-sm
                 [&::-webkit-calendar-picker-indicator]:cursor-pointer"
        >
      </form>

      {{-- Buscador multicriterio (Ticket, Cliente o Vendedor) --}}
      <form method="GET" action="{{ route('tickets.index') }}" class="flex items-center gap-2">
        <input type="hidden" name="fecha" value="{{ $fecha }}">
        @if(request('estado'))
          <input type="hidden" name="estado" value="{{ request('estado') }}">
        @endif
        <input
          type="text"
          name="buscar"
          value="{{ request('buscar') }}"
          placeholder="Buscar ticket, cliente, vendedor..."
          class="bg-white border border-purple-200 rounded-lg px-3 h-9
                 text-slate-900 text-sm outline-none w-56 shadow-sm
                 focus:border-purple-600 transition-colors placeholder-slate-400"
        >
        <button type="submit"
          class="flex items-center justify-center w-9 h-9 bg-white border border-purple-200
                 rounded-lg hover:border-purple-600 hover:text-purple-600 text-slate-500
                 transition-colors shrink-0 shadow-sm">
          <i data-lucide="search" class="w-3.5 h-3.5" style="stroke-width:1.75"></i>
        </button>
      </form>

      {{-- Filtros Rápidos por Estado --}}
      <div class="flex items-center gap-1.5 ml-auto">
        <a href="{{ route('tickets.index', ['fecha' => $fecha, 'buscar' => request('buscar')]) }}"
           class="px-3.5 h-9 inline-flex items-center text-xs font-semibold rounded-lg border transition-colors shadow-sm {{ !request('estado') ? 'bg-purple-50 border-purple-300 text-purple-700' : 'bg-white border-purple-200 text-slate-700 hover:text-slate-900 hover:border-purple-300' }}">
          Todos
        </a>
        <a href="{{ route('tickets.index', ['fecha' => $fecha, 'buscar' => request('buscar'), 'estado' => 'PENDIENTE']) }}"
           class="px-3.5 h-9 inline-flex items-center text-xs font-semibold rounded-lg border transition-colors shadow-sm {{ request('estado') === 'PENDIENTE' ? 'bg-purple-50 border-purple-300 text-purple-700' : 'bg-white border-purple-200 text-slate-700 hover:text-slate-900 hover:border-purple-300' }}">
          Pendientes
        </a>
        <a href="{{ route('tickets.index', ['fecha' => $fecha, 'buscar' => request('buscar'), 'estado' => 'PREPARANDO']) }}"
           class="px-3.5 h-9 inline-flex items-center text-xs font-semibold rounded-lg border transition-colors shadow-sm {{ request('estado') === 'PREPARANDO' ? 'bg-amber-50 border-amber-300 text-amber-800' : 'bg-white border-purple-200 text-slate-700 hover:text-slate-900 hover:border-purple-300' }}">
          Preparando
        </a>
        <a href="{{ route('tickets.index', ['fecha' => $fecha, 'buscar' => request('buscar'), 'estado' => 'COMPLETADO']) }}"
           class="px-3.5 h-9 inline-flex items-center text-xs font-semibold rounded-lg border transition-colors shadow-sm {{ request('estado') === 'COMPLETADO' ? 'bg-emerald-50 border-emerald-300 text-emerald-800' : 'bg-white border-purple-200 text-slate-700 hover:text-slate-900 hover:border-purple-300' }}">
          Completados
        </a>
      </div>

    </div>
  </div>

  {{-- Tarjeta contenedora de la grilla --}}
  <div class="bg-white border border-purple-200 rounded-xl shadow-sm overflow-hidden">
    
    {{-- Cabecera de la Grilla (5 columnas) --}}
    <div class="hidden md:flex items-center gap-3 px-4 py-3
                text-[11px] font-bold text-slate-600 uppercase tracking-widest
                border-b border-purple-200 bg-purple-50/30">
      <div class="w-6 shrink-0"></div>
      <div class="grid grid-cols-[2fr_1fr_1fr_1fr_0.7fr] gap-2 flex-1">
        <div>Cliente</div>
        <div>Ticket</div>
        <div>Hora</div>
        <div>Vendedor</div>
        <div class="text-center">Estado</div>
      </div>
    </div>

    <div class="divide-y divide-slate-100">
      @forelse($tickets as $ticket)
        @php
            $taskStatus = optional($ticket->pickingTask)->status ?? 'PENDIENTE';
        @endphp
        <a href="{{ route('tickets.show', $ticket->ticket_number) }}"
           class="flex items-center gap-3 px-4 py-4
                  hover:bg-purple-50/40 transition-colors cursor-pointer">
          <div class="w-6 shrink-0 text-center text-xs text-slate-500 font-mono font-semibold">
            {{ $loop->iteration }}
          </div>
          
          {{-- Fila de la Grilla (5 columnas sincronizadas) --}}
          <div class="grid grid-cols-2 md:grid-cols-[2fr_1fr_1fr_1fr_0.7fr] gap-2 flex-1 items-center">
            <div class="text-sm font-bold text-slate-900">{{ $ticket->customer ?? '—' }}</div>
            
            {{-- Nro de ticket en negrita --}}
            <div class="text-sm font-semibold text-slate-700 font-mono">{{ $ticket->ticket_number }}</div>
            
            {{-- Hora del ticket formateada --}}
            <div class="text-xs text-slate-600 font-mono font-medium">
              {{ $ticket->created_at ? $ticket->created_at->format('H:i') : '—' }}
            </div>
            
            <div class="hidden md:block text-sm text-slate-700 font-medium">{{ $ticket->seller ?? '—' }}</div>
            
            <div class="text-right md:text-center">
              @if($taskStatus === 'COMPLETADO')
                <span class="inline-block px-2.5 py-0.5 text-[11px] font-black tracking-wider
                             bg-emerald-100 text-emerald-800 border border-emerald-200 rounded-md">
                  COMPLETADO
                </span>
              @elseif($taskStatus === 'PREPARANDO')
                <span class="inline-block px-2.5 py-0.5 text-[11px] font-black tracking-wider
                             bg-amber-100 text-amber-800 border border-amber-200 rounded-md">
                  PREPARANDO
                </span>
              @else
                <span class="inline-block px-2.5 py-0.5 text-[11px] font-black tracking-wider
                             bg-purple-100 text-purple-800 border border-purple-200 rounded-md">
                  PENDIENTE
                </span>
              @endif
            </div>
          </div>
        </a>
      @empty
        <div class="px-4 py-16 text-center text-slate-500 text-sm font-medium">
          @if(request('buscar') || request('estado'))
            Sin resultados para los filtros seleccionados.
          @else
            Sin tickets para el {{ \Carbon\Carbon::parse($fecha)->format('d-m-Y') }}
          @endif
        </div>
      @endforelse
    </div>

  </div>

@endsection