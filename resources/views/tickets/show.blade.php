@extends('layouts.app')

@section('content')

  <div class="mb-6 flex items-center gap-3">
    <a href="{{ route('tickets.index') }}"
       class="flex items-center gap-1.5 text-white/35 hover:text-white/70 text-sm transition-colors">
      <i data-lucide="arrow-left" class="w-4 h-4" style="stroke-width:1.5"></i>
      Volver
    </a>
    <span class="text-white/15">/</span>
    <span class="text-white/55 text-sm">Ticket #{{ $ticket->ticket_number }}</span>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">

    <div class="bg-gray-900 border border-white/[0.06] rounded-xl px-5 py-4">
      <p class="text-[11px] text-white/35 uppercase tracking-widest mb-1">Ticket</p>
      <p class="text-lg font-semibold text-white">#{{ $ticket->ticket_number }}</p>
    </div>

    <div class="bg-gray-900 border border-white/[0.06] rounded-xl px-5 py-4">
      <p class="text-[11px] text-white/35 uppercase tracking-widest mb-1">Picker</p>
      <p class="text-lg font-semibold text-white">{{ $ticket->seller->name ?? '—' }}</p>
    </div>

    <div class="bg-gray-900 border border-white/[0.06] rounded-xl px-5 py-4">
      <p class="text-[11px] text-white/35 uppercase tracking-widest mb-1">Total</p>
      <p class="text-lg font-semibold text-white">${{ number_format($ticket->total_amount, 0, ',', '.') }}</p>
    </div>

  </div>

  <div class="hidden md:grid md:grid-cols-4 px-4 py-2.5
              text-[11px] font-semibold text-white/30 uppercase tracking-widest
              border-b border-white/[0.07]">
    <div class="col-span-2">Producto</div>
    <div class="text-center">Cantidad</div>
    <div class="text-right">Precio</div>
  </div>

  <div class="divide-y divide-white/[0.05]">
    @forelse($ticket->items as $item)
      <div class="grid grid-cols-2 md:grid-cols-4 items-center px-4 py-3.5 hover:bg-slate-800/20 transition-colors">
        <div class="col-span-2 text-sm text-white">{{ $item->description ?? $item->name ?? '—' }}</div>
        <div class="hidden md:block text-sm text-white/55 text-center">{{ $item->quantity }}</div>
        <div class="text-sm text-white/55 text-right">${{ number_format($item->price, 0, ',', '.') }}</div>
      </div>
    @empty
      <div class="px-4 py-8 text-center text-white/25 text-sm">Sin ítems registrados</div>
    @endforelse
  </div>

@endsection