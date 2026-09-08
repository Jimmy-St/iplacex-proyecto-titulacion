@extends('layouts.app')

@section('content')

  {{-- Cabecera, Selector de Fecha Automático y Botón de Exportación XLS --}}
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
      <h2 class="text-slate-900 font-bold text-lg">Reportes y Rendimiento</h2>
      <p class="text-slate-600 text-sm mt-1 font-medium">Resumen de actividad operativa y tiempos de ciclo</p>
    </div>

    <div class="flex items-center gap-3">
      {{-- Botón Descarga XLS Detallado --}}
      <a href="{{ route('reportes.export', ['fecha' => $fecha]) }}" 
         class="flex items-center gap-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold px-4 py-2 rounded-lg text-xs tracking-wide shadow-md shadow-purple-600/20 transition-all cursor-pointer">
        <i data-lucide="file-spreadsheet" class="w-4 h-4" style="stroke-width:2"></i>
        <span>Exportar Detalle XLS</span>
      </a>

      {{-- Selector de Fecha --}}
      <form method="GET" action="{{ route('reportes.index') }}" class="flex items-center gap-2">
        <div class="relative">
          <input type="date" name="fecha" value="{{ $fecha }}" 
                 onchange="this.form.submit()"
                 class="px-3.5 py-2 bg-white border border-purple-200 rounded-lg text-sm font-semibold text-slate-900 focus:outline-none focus:border-purple-600 shadow-sm cursor-pointer">
        </div>
      </form>
    </div>
  </div>

  {{-- KPIs Globales (5 Columnas: Incluye En Proceso) --}}
  <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-8">

    <div class="relative bg-white border border-purple-200 rounded-xl px-4 py-4 shadow-sm overflow-hidden">
        <div class="absolute -right-4 -bottom-6 pointer-events-none text-slate-50 transform -rotate-12">
            <i data-lucide="ticket" class="w-32 h-32"></i>
        </div>
        <div class="relative z-10">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tickets fecha</p>
            <p class="text-2xl font-bold text-slate-900">{{ $totalTickets }}</p>
        </div>
    </div>

    <div class="relative bg-white border border-purple-200 rounded-xl px-4 py-4 shadow-sm overflow-hidden">
        <div class="absolute -right-4 -bottom-9 pointer-events-none text-amber-50 transform -rotate-12">
            <i data-lucide="clipboard-clock" class="w-32 h-32"></i>
        </div>
        <div class="relative z-10">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Pendientes</p>
            <p class="text-2xl font-bold text-amber-900">{{ $pendientes }}</p>
        </div>
    </div>

    <div class="relative bg-white border border-purple-200 rounded-xl px-4 py-4 shadow-sm overflow-hidden">
        <div class="absolute -right-4 -bottom-8 pointer-events-none text-blue-50 transform -rotate-12">
            <i data-lucide="list-checks" class="w-32 h-32"></i>
        </div>
        <div class="relative z-10">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">En Proceso</p>
            <p class="text-2xl font-bold text-blue-900">{{ $enProceso }}</p>
        </div>
    </div>

    <div class="relative bg-white border border-purple-200 rounded-xl px-4 py-4 shadow-sm overflow-hidden">
        <div class="absolute -right-4 -bottom-6 pointer-events-none text-emerald-50 transform -rotate-12">
            <i data-lucide="ticket-check" class="w-32 h-32"></i>
        </div>
        <div class="relative z-10">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Completados</p>
            <p class="text-2xl font-bold text-emerald-900">{{ $completados }}</p>
        </div>
    </div>

    <div class="relative bg-white border border-purple-200 rounded-xl px-4 py-4 shadow-sm overflow-hidden">
        <div class="absolute -right-4 -bottom-11 pointer-events-none text-purple-50 transform -rotate-12">
            <i data-lucide="timer" class="w-32 h-32"></i>
        </div>
        <div class="relative z-10">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tiempo promedio</p>
            <p class="text-2xl font-bold text-purple-900">{{ $tiempoPromedioCiclo }}</p>
        </div>
    </div>
  </div>

  {{-- Sección: Desempeño por Picker --}}
  <div class="bg-white border border-purple-200 rounded-xl overflow-hidden shadow-sm">
    <div class="px-5 py-4 border-b border-purple-100 flex items-center justify-between bg-slate-50/50">
      <h3 class="text-slate-900 font-bold text-sm">Rendimiento por Preparador (Picker)</h3>
      <span class="text-xs font-semibold text-slate-500">Mostrando fecha: {{ $fechaFormateada }}</span>
    </div>

    {{-- Cabecera de Tabla --}}
    <div class="hidden md:grid md:grid-cols-12 px-4 py-3 text-[11px] font-bold text-slate-600 uppercase tracking-widest border-b border-purple-200 bg-purple-50/30">
      <div class="col-span-3">PICKER</div>
      <div class="col-span-2">ZONA</div>
      <div class="col-span-2 text-center">COMPLETADOS</div>
      <div class="col-span-3 text-center">TIEMPO PROMEDIO</div>
      <div class="col-span-2 text-right">CARGA ACTUAL</div>
    </div>

    {{-- Listado Dinámico --}}
    <div class="divide-y divide-slate-100">
      @forelse($rendimientoPickers as $pickerData)
        <div class="grid grid-cols-2 md:grid-cols-12 items-center px-4 py-3.5 hover:bg-purple-50/40 transition-colors gap-2 md:gap-0">
          <div class="col-span-2 md:col-span-3">
            <div class="text-sm font-bold text-slate-900 uppercase">{{ $pickerData['display_name'] }}</div>
            <div class="text-xs font-mono font-semibold text-slate-500">{{ $pickerData['employee_code'] }}</div>
          </div>
          <div class="hidden md:block md:col-span-2 text-xs font-semibold text-slate-600">{{ $pickerData['zone_assigned'] }}</div>
          <div class="col-span-1 md:col-span-2 md:text-center text-sm font-bold text-emerald-700">{{ $pickerData['completados'] }}</div>
          <div class="col-span-1 md:col-span-3 md:text-center text-sm font-semibold text-slate-700 font-mono">{{ $pickerData['tiempo_promedio'] }} min</div>
          <div class="col-span-2 md:col-span-2 text-right">
            <span class="inline-block px-2.5 py-0.5 text-[10px] font-bold tracking-wider {{ str_replace(['red-', 'green-', 'blue-', 'amber-', 'rose-', 'emerald-'], ['rose-', 'emerald-', 'purple-', 'amber-', 'rose-', 'emerald-'], $pickerData['badge_clase']) }} border rounded-md shadow-sm">
              {{ $pickerData['badge_estado'] }}
            </span>
          </div>
        </div>
      @empty
        <div class="px-4 py-16 text-center text-sm text-slate-400 font-medium">
          No hay pickers registrados en el sistema.
        </div>
      @endforelse
    </div>
  </div>

@endsection