@extends('layouts.app')

@section('content')

  <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
      <a href="{{ route('pickers.index') }}" class="inline-flex items-center gap-1.5 text-xs text-white/40 hover:text-white/80 mb-2 transition-colors">
        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
        Volver al listado
      </a>
      <div class="flex items-center gap-3">
        <h2 class="text-white font-semibold text-xl">{{ $picker->display_name }}</h2>
        @if(!$picker->is_active)
          <span class="px-2.5 py-0.5 text-[10px] font-bold tracking-wider bg-red-500/15 text-red-400 border border-red-500/25 rounded-md">
            INACTIVO
          </span>
        @elseif($picker->status === 'busy')
          <span class="px-2.5 py-0.5 text-[10px] font-bold tracking-wider bg-amber-500/15 text-amber-400 border border-amber-500/25 rounded-md">
            EN PICKING
          </span>
        @else
          <span class="px-2.5 py-0.5 text-[10px] font-bold tracking-wider bg-green-500/15 text-green-400 border border-green-500/25 rounded-md">
            ACTIVO
          </span>
        @endif
      </div>
      <p class="text-white/35 text-xs mt-1">{{ $picker->full_name }} &bull; {{ $picker->employee_code ?? 'Sin código' }}</p>
    </div>

    <div class="flex items-center gap-2">
      <a href="{{ route('pickers.edit', $picker) }}" 
         class="inline-flex items-center gap-2 px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-white text-xs font-medium rounded-lg transition-colors border border-white/[0.08]">
        <i data-lucide="edit-2" class="w-3.5 h-3.5"></i>
        Editar Ficha
      </a>
    </div>
  </div>

  {{-- Tarjetas de Resumen Rápido --}}
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-gray-900/60 border border-white/[0.06] rounded-xl p-4">
      <span class="text-xs text-white/40 font-medium">Zona Asignada</span>
      <div class="text-base font-bold text-white mt-1">{{ $picker->zone_assigned ?? 'General' }}</div>
    </div>
    <div class="bg-gray-900/60 border border-white/[0.06] rounded-xl p-4">
      <span class="text-xs text-white/40 font-medium">Estado Operativo</span>
      <div class="text-base font-bold text-purple-400 uppercase mt-1">{{ $picker->status }}</div>
    </div>
    <div class="bg-gray-900/60 border border-white/[0.06] rounded-xl p-4">
      <span class="text-xs text-white/40 font-medium">Registrado el</span>
      <div class="text-base font-bold text-white mt-1">{{ $picker->created_at ? $picker->created_at->format('d/m/Y') : '—' }}</div>
    </div>
  </div>

  {{-- Contenedor para futuras Tareas / Tickets --}}
  <div class="bg-gray-900/40 border border-white/[0.06] rounded-xl p-6">
    <div class="flex items-center justify-between pb-4 border-b border-white/[0.06] mb-6">
      <div>
        <h3 class="text-white font-semibold text-sm">Tareas & Tickets Asignados</h3>
        <p class="text-white/35 text-xs mt-0.5">Historial y actividad actual en el ERP Bicom</p>
      </div>
      <span class="text-xs text-white/40">0 activas</span>
    </div>

    {{-- Estado vacío temporal mientras integramos tareas --}}
    <div class="py-12 text-center text-white/30 text-xs">
      <i data-lucide="package-search" class="w-8 h-8 mx-auto mb-2 opacity-40"></i>
      No hay tareas ni tickets asignados en este momento.
    </div>
  </div>

@endsection