@extends('layouts.app')

@section('content')

  <div class="mb-6">
    <h2 class="text-white font-semibold text-lg">Reportes</h2>
    <p class="text-white/35 text-sm mt-1">Resumen de actividad</p>
  </div>

  <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-8">

    <div class="bg-gray-900 border border-white/[0.06] rounded-xl px-4 py-4">
      <p class="text-[11px] text-white/35 uppercase tracking-widest mb-1">Tickets hoy</p>
      <p class="text-2xl font-semibold text-white">0</p>
    </div>

    <div class="bg-gray-900 border border-white/[0.06] rounded-xl px-4 py-4">
      <p class="text-[11px] text-white/35 uppercase tracking-widest mb-1">Pendientes</p>
      <p class="text-2xl font-semibold text-red-400">0</p>
    </div>

    <div class="bg-gray-900 border border-white/[0.06] rounded-xl px-4 py-4">
      <p class="text-[11px] text-white/35 uppercase tracking-widest mb-1">Completados</p>
      <p class="text-2xl font-semibold text-green-400">0</p>
    </div>

    <div class="bg-gray-900 border border-white/[0.06] rounded-xl px-4 py-4">
      <p class="text-[11px] text-white/35 uppercase tracking-widest mb-1">Pickers activos</p>
      <p class="text-2xl font-semibold text-purple-400">0</p>
    </div>

  </div>

@endsection