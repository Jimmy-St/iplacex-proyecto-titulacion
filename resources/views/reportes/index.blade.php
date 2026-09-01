@extends('layouts.app')

@section('content')

  {{-- Cabecera y Selector de Fecha --}}
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
      <h2 class="text-white font-semibold text-lg">Reportes y Rendimiento</h2>
      <p class="text-white/35 text-sm mt-1">Resumen de actividad operativa y tiempos de ciclo</p>
    </div>

    {{-- Buscador / Filtro de Fecha (Estático por ahora) --}}
    <div class="flex items-center gap-2">
      <input type="date" value="{{ date('Y-m-d') }}" 
             class="px-3 py-2 bg-gray-900 border border-white/[0.08] rounded-lg text-sm text-white focus:outline-none focus:border-purple-500/50">
      <button type="button" class="px-4 py-2 bg-purple-600 hover:bg-purple-500 text-white text-sm font-medium rounded-lg transition-colors">
        Filtrar
      </button>
    </div>
  </div>

  {{-- KPIs Globales (Tarjetas de Resumen) --}}
  <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-8">

    <div class="bg-gray-900 border border-white/[0.06] rounded-xl px-4 py-4">
      <p class="text-[11px] text-white/35 uppercase tracking-widest mb-1">Tickets hoy</p>
      <p class="text-2xl font-semibold text-white">40</p>
    </div>

    <div class="bg-gray-900 border border-white/[0.06] rounded-xl px-4 py-4">
      <p class="text-[11px] text-white/35 uppercase tracking-widest mb-1">Pendientes</p>
      <p class="text-2xl font-semibold text-red-400">5</p>
    </div>

    <div class="bg-gray-900 border border-white/[0.06] rounded-xl px-4 py-4">
      <p class="text-[11px] text-white/35 uppercase tracking-widest mb-1">Completados</p>
      <p class="text-2xl font-semibold text-green-400">35</p>
    </div>

    <div class="bg-gray-900 border border-white/[0.06] rounded-xl px-4 py-4">
      <p class="text-[11px] text-white/35 uppercase tracking-widest mb-1">Tiempo Prom. Ciclo</p>
      <p class="text-2xl font-semibold text-purple-400">12.4 min</p>
    </div>

  </div>

  {{-- Sección: Desempeño por Picker --}}
  <div class="bg-gray-900 border border-white/[0.06] rounded-xl overflow-hidden">
    <div class="px-5 py-4 border-b border-white/[0.06] flex items-center justify-between">
      <h3 class="text-white font-semibold text-sm">Rendimiento por Preparador (Picker)</h3>
      <span class="text-xs text-white/40">Datos del día actual</span>
    </div>

    {{-- Cabecera de Tabla --}}
    <div class="hidden md:grid md:grid-cols-12 px-4 py-2.5 text-[11px] font-semibold text-white/30 uppercase tracking-widest border-b border-white/[0.07]">
      <div class="col-span-3">PICKER</div>
      <div class="col-span-2">ZONA</div>
      <div class="col-span-2 text-center">COMPLETADOS</div>
      <div class="col-span-3 text-center">TIEMPO PROMEDIO</div>
      <div class="col-span-2 text-right">CARGA ACTUAL</div>
    </div>

    {{-- Listado Mock (Ejemplo visual) --}}
    <div class="divide-y divide-white/[0.05]">
      
      {{-- Fila Mock 1 --}}
      <div class="grid grid-cols-2 md:grid-cols-12 items-center px-4 py-3.5 hover:bg-slate-800/30 transition-colors gap-2 md:gap-0">
        <div class="col-span-2 md:col-span-3">
          <div class="text-sm font-bold text-white uppercase">ALAN M.</div>
          <div class="text-xs text-white/30 font-mono">PCK-001</div>
        </div>
        <div class="hidden md:block md:col-span-2 text-xs text-white/50">General</div>
        <div class="col-span-1 md:col-span-2 md:text-center text-sm font-semibold text-green-400">14</div>
        <div class="col-span-1 md:col-span-3 md:text-center text-sm text-white/80 font-mono">9.5 min</div>
        <div class="col-span-2 md:col-span-2 text-right">
          <span class="inline-block px-2.5 py-0.5 text-[10px] font-bold tracking-wider bg-rose-500/15 text-rose-400 border border-rose-500/25 rounded-md">
            EN PICKING (2)
          </span>
        </div>
      </div>

      {{-- Fila Mock 2 --}}
      <div class="grid grid-cols-2 md:grid-cols-12 items-center px-4 py-3.5 hover:bg-slate-800/30 transition-colors gap-2 md:gap-0">
        <div class="col-span-2 md:col-span-3">
          <div class="text-sm font-bold text-white uppercase">ANTONY E.</div>
          <div class="text-xs text-white/30 font-mono">PCK-002</div>
        </div>
        <div class="hidden md:block md:col-span-2 text-xs text-white/50">General</div>
        <div class="col-span-1 md:col-span-2 md:text-center text-sm font-semibold text-green-400">12</div>
        <div class="col-span-1 md:col-span-3 md:text-center text-sm text-white/80 font-mono">14.1 min</div>
        <div class="col-span-2 md:col-span-2 text-right">
          <span class="inline-block px-2.5 py-0.5 text-[10px] font-bold tracking-wider bg-emerald-500/15 text-emerald-400 border border-emerald-500/25 rounded-md">
            LIBRE (0)
          </span>
        </div>
      </div>

      {{-- Fila Mock 3 --}}
      <div class="grid grid-cols-2 md:grid-cols-12 items-center px-4 py-3.5 hover:bg-slate-800/30 transition-colors gap-2 md:gap-0">
        <div class="col-span-2 md:col-span-3">
          <div class="text-sm font-bold text-white uppercase">BREIMER A.</div>
          <div class="text-xs text-white/30 font-mono">PCK-003</div>
        </div>
        <div class="hidden md:block md:col-span-2 text-xs text-white/50">General</div>
        <div class="col-span-1 md:col-span-2 md:text-center text-sm font-semibold text-green-400">9</div>
        <div class="col-span-1 md:col-span-3 md:text-center text-sm text-white/80 font-mono">11.8 min</div>
        <div class="col-span-2 md:col-span-2 text-right">
          <span class="inline-block px-2.5 py-0.5 text-[10px] font-bold tracking-wider bg-emerald-500/15 text-emerald-400 border border-emerald-500/25 rounded-md">
            LIBRE (0)
          </span>
        </div>
      </div>

    </div>
  </div>

@endsection