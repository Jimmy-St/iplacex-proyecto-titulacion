@extends('layouts.app')

@section('content')

  <div class="mb-6">
    <span class="text-[11px] text-white/35 uppercase tracking-widest block mb-2">Tickets del día</span>
    <div class="flex items-center gap-2">

      {{-- Filtro fecha — submit automático al cambiar --}}
      <form method="GET" action="{{ route('tickets.index') }}">
        @if(request('buscar'))
          <input type="hidden" name="buscar" value="{{ request('buscar') }}">
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

    </div>
  </div>

  <div class="hidden md:grid md:grid-cols-4 px-4 py-2.5
              text-[11px] font-semibold text-white/30 uppercase tracking-widest
              border-b border-white/[0.07]">
    <div>Cliente</div>
    <div>Ticket</div>
    <div>Picker</div>
    <div class="text-center">Estado</div>
  </div>

  <div class="divide-y divide-white/[0.05]">
    @forelse($tickets as $ticket)
      <a href="{{ route('tickets.show', $ticket->ticket_number) }}"
         class="grid grid-cols-2 md:grid-cols-4 items-center px-4 py-4
                hover:bg-slate-800/20 transition-colors cursor-pointer">
        <div class="text-sm font-bold text-white">{{ strtoupper($ticket->seller->name ?? '—') }}</div>
        <div class="text-sm text-white/55">{{ $ticket->ticket_number }}</div>
        <div class="hidden md:block text-sm text-white/55">{{ $ticket->seller->employee_code ?? '—' }}</div>
        <div class="text-right md:text-center">
          @if($ticket->status === 'pending')
            <span class="inline-block px-2.5 py-0.5 text-[11px] font-black tracking-wider
                         bg-red-500/15 text-red-400 border border-red-500/25 rounded-md">
              PEND.
            </span>
          @else
            <span class="inline-block px-2.5 py-0.5 text-[11px] font-black tracking-wider
                         bg-green-500/15 text-green-400 border border-green-500/25 rounded-md">
              OK
            </span>
          @endif
        </div>
      </a>
    @empty
      <div class="px-4 py-12 text-center text-white/25 text-sm">
        @if(request('buscar'))
          Sin resultados para "{{ request('buscar') }}"
        @else
          Sin tickets para el {{ \Carbon\Carbon::parse($fecha)->format('d-m-Y') }}
        @endif
      </div>
    @endforelse
  </div>

@endsection