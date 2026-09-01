@extends('layouts.app')

@section('content')

  <div class="mb-6">
    <span class="text-[11px] text-white/35 uppercase tracking-widest block mb-2">Tickets del día</span>
    
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
          class="bg-gray-900 border border-white/10 rounded-lg px-3 h-9
                 text-white font-semibold text-base outline-none
                 focus:border-purple-500 cursor-pointer transition-colors
                 [&::-webkit-calendar-picker-indicator]:invert
                 [&::-webkit-calendar-picker-indicator]:brightness-125
                 [&::-webkit-calendar-picker-indicator]:cursor-pointer"
        >
      </form>

      {{-- Buscador por número — submit con botón --}}
      <form method="GET" action="{{ route('tickets.index') }}" class="flex items-center gap-2">
        <input type="hidden" name="fecha" value="{{ $fecha }}">
        @if(request('estado'))
          <input type="hidden" name="estado" value="{{ request('estado') }}">
        @endif
        <input
          type="text"
          name="buscar"
          value="{{ request('buscar') }}"
          placeholder="Nº ticket..."
          class="bg-gray-900 border border-white/10 rounded-lg px-3 h-9
                 text-white text-sm outline-none w-36
                 focus:border-purple-500 transition-colors placeholder-white/20"
        >
        <button type="submit"
          class="flex items-center justify-center w-9 h-9 bg-gray-900 border border-white/10
                 rounded-lg hover:border-purple-500 hover:text-purple-400 text-white/40
                 transition-colors shrink-0">
          <i data-lucide="search" class="w-3.5 h-3.5" style="stroke-width:1.5"></i>
        </button>
      </form>

      {{-- Filtros Rápidos por Estado --}}
      <div class="flex items-center gap-1.5 ml-auto">
        <a href="{{ route('tickets.index', ['fecha' => $fecha, 'buscar' => request('buscar')]) }}"
           class="px-3 h-9 inline-flex items-center text-xs font-medium rounded-lg border transition-colors {{ !request('estado') ? 'bg-purple-600/20 border-purple-500/50 text-purple-300' : 'bg-gray-900 border-white/10 text-white/50 hover:text-white' }}">
          Todos
        </a>
        <a href="{{ route('tickets.index', ['fecha' => $fecha, 'buscar' => request('buscar'), 'estado' => 'PENDIENTE']) }}"
           class="px-3 h-9 inline-flex items-center text-xs font-medium rounded-lg border transition-colors {{ request('estado') === 'PENDIENTE' ? 'bg-purple-600/20 border-purple-500/50 text-purple-300' : 'bg-gray-900 border-white/10 text-white/50 hover:text-white' }}">
          Pendientes
        </a>
        <a href="{{ route('tickets.index', ['fecha' => $fecha, 'buscar' => request('buscar'), 'estado' => 'PREPARANDO']) }}"
           class="px-3 h-9 inline-flex items-center text-xs font-medium rounded-lg border transition-colors {{ request('estado') === 'PREPARANDO' ? 'bg-amber-500/20 border-amber-500/50 text-amber-300' : 'bg-gray-900 border-white/10 text-white/50 hover:text-white' }}">
          Preparando
        </a>
        <a href="{{ route('tickets.index', ['fecha' => $fecha, 'buscar' => request('buscar'), 'estado' => 'COMPLETADO']) }}"
           class="px-3 h-9 inline-flex items-center text-xs font-medium rounded-lg border transition-colors {{ request('estado') === 'COMPLETADO' ? 'bg-emerald-500/20 border-emerald-500/50 text-emerald-300' : 'bg-gray-900 border-white/10 text-white/50 hover:text-white' }}">
          Completados
        </a>
      </div>

    </div>
  </div>

  <div class="hidden md:flex items-center gap-3 px-4 py-2.5
              text-[11px] font-semibold text-white/30 uppercase tracking-widest
              border-b border-white/[0.07]">
    <div class="w-6 shrink-0"></div>
    <div class="grid grid-cols-[2fr_1fr_1fr_0.7fr] gap-2 flex-1">
      <div>Cliente</div>
      <div>Ticket</div>
      <div>Vendedor</div>
      <div class="text-center">Estado</div>
    </div>
  </div>

  <div class="divide-y divide-white/[0.05]">
    @forelse($tickets as $ticket)
      @php
          $taskStatus = optional($ticket->pickingTask)->status ?? 'PENDIENTE';
      @endphp
      <a href="{{ route('tickets.show', $ticket->ticket_number) }}"
         class="flex items-center gap-3 px-4 py-4
                hover:bg-slate-800/20 transition-colors cursor-pointer">
        <div class="w-6 shrink-0 text-center text-[11px] text-white/25 font-mono">
          {{ $loop->iteration }}
        </div>
        <div class="grid grid-cols-2 md:grid-cols-[2fr_1fr_1fr_0.7fr] gap-2 flex-1 items-center">
          <div class="text-sm font-bold text-white">{{ $ticket->customer ?? '—' }}</div>
          <div class="text-sm text-white/55">{{ $ticket->ticket_number }}</div>
          <div class="hidden md:block text-sm text-white/55">{{ $ticket->seller ?? '—' }}</div>
          <div class="text-right md:text-center">
            @if($taskStatus === 'COMPLETADO')
              <span class="inline-block px-2.5 py-0.5 text-[11px] font-black tracking-wider
                           bg-emerald-500/15 text-emerald-400 border border-emerald-500/25 rounded-md">
                COMPLETADO
              </span>
            @elseif($taskStatus === 'PREPARANDO')
              <span class="inline-block px-2.5 py-0.5 text-[11px] font-black tracking-wider
                           bg-amber-500/15 text-amber-400 border border-amber-500/25 rounded-md">
                PREPARANDO
              </span>
            @else
              <span class="inline-block px-2.5 py-0.5 text-[11px] font-black tracking-wider
                           bg-purple-500/15 text-purple-400 border border-purple-500/25 rounded-md">
                PENDIENTE
              </span>
            @endif
          </div>
        </div>
      </a>
    @empty
      <div class="px-4 py-12 text-center text-white/25 text-sm">
        @if(request('buscar') || request('estado'))
          Sin resultados para los filtros seleccionados.
        @else
          Sin tickets para el {{ \Carbon\Carbon::parse($fecha)->format('d-m-Y') }}
        @endif
      </div>
    @endforelse
  </div>

@endsection