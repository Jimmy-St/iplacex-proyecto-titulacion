@extends('layouts.app')

@section('content')

  <div class="mb-6">
    <h2 class="text-white font-semibold text-lg">Pickers</h2>
    <p class="text-white/35 text-sm mt-1">Gestión de pickers activos</p>
  </div>

  <div class="hidden md:grid md:grid-cols-3 px-4 py-2.5
              text-[11px] font-semibold text-white/30 uppercase tracking-widest
              border-b border-white/[0.07]">
    <div>Nombre</div>
    <div>Tickets asignados</div>
    <div class="text-center">Estado</div>
  </div>

  <div class="divide-y divide-white/[0.05]">
    {{-- @foreach($pickers as $picker) --}}
    <div class="grid grid-cols-2 md:grid-cols-3 items-center px-4 py-4 hover:bg-slate-800/20 transition-colors">
      <div class="text-sm font-bold text-white">M. GONZALEZ</div>
      <div class="text-sm text-white/55">3</div>
      <div class="text-right md:text-center">
        <span class="inline-block px-2.5 py-0.5 text-[11px] font-black tracking-wider
                     bg-green-500/15 text-green-400 border border-green-500/25 rounded-md">
          ACTIVO
        </span>
      </div>
    </div>
    {{-- @endforeach --}}
  </div>

@endsection