@extends('layouts.app')

@section('content')

  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
      <h2 class="text-slate-900 font-bold text-lg">Pickers</h2>
      <p class="text-slate-600 text-sm mt-1 font-medium">Gestión de preparadores de pedidos</p>
    </div>
    <a href="{{ route('pickers.create') }}" 
       class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold rounded-lg transition-colors shrink-0 shadow-md shadow-purple-600/20 cursor-pointer">
      <i data-lucide="user-plus" class="w-4 h-4" style="stroke-width:1.75"></i>
      Nuevo Picker
    </a>
  </div>

  @if(session('success'))
    <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold rounded-lg flex items-center gap-2 shadow-sm">
      <i data-lucide="check-circle" class="w-4 h-4 shrink-0 text-emerald-600" style="stroke-width:1.75"></i>
      {{ session('success') }}
    </div>
  @endif

  {{-- Barra de búsqueda y filtros de estado --}}
  <div class="mb-5 flex flex-wrap items-center justify-between gap-4">
    <form method="GET" action="{{ route('pickers.index') }}" class="flex items-center gap-2 w-full max-w-sm">
      @if(request('estado'))
        <input type="hidden" name="estado" value="{{ request('estado') }}">
      @endif
      <div class="relative w-full">
        <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" style="stroke-width:1.75"></i>
        <input type="text" name="search" value="{{ $search ?? '' }}" 
               placeholder="Buscar por nombre, código o zona..." 
               class="w-full pl-9 pr-3 py-2 bg-white border border-purple-200 rounded-lg text-sm font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:border-purple-600 shadow-sm transition-colors">
      </div>
      @if(!empty($search))
        <a href="{{ route('pickers.index', ['estado' => request('estado')]) }}" class="text-xs font-semibold text-slate-500 hover:text-slate-900 underline shrink-0">Limpiar</a>
      @endif
    </form>

    {{-- Filtros Rápidos por Estado de Picker --}}
    <div class="flex items-center gap-1.5 ml-auto">
      <a href="{{ route('pickers.index', ['search' => request('search')]) }}"
         class="px-3.5 h-9 inline-flex items-center text-xs font-semibold rounded-lg border transition-colors shadow-sm {{ !request('estado') ? 'bg-purple-50 border-purple-300 text-purple-700' : 'bg-white border-purple-200 text-slate-700 hover:text-slate-900 hover:border-purple-300' }}">
        Todos
      </a>
      <a href="{{ route('pickers.index', ['search' => request('search'), 'estado' => 'disponibles']) }}"
         class="px-3.5 h-9 inline-flex items-center text-xs font-semibold rounded-lg border transition-colors shadow-sm {{ request('estado') === 'disponibles' ? 'bg-emerald-50 border-emerald-300 text-emerald-800' : 'bg-white border-purple-200 text-slate-700 hover:text-slate-900 hover:border-purple-300' }}">
        Disponibles
      </a>
      <a href="{{ route('pickers.index', ['search' => request('search'), 'estado' => 'picking']) }}"
         class="px-3.5 h-9 inline-flex items-center text-xs font-semibold rounded-lg border transition-colors shadow-sm {{ request('estado') === 'picking' ? 'bg-rose-50 border-rose-300 text-rose-800' : 'bg-white border-purple-200 text-slate-700 hover:text-slate-900 hover:border-purple-300' }}">
        Picking
      </a>
    </div>
  </div>

  {{-- Tarjeta contenedora de la tabla --}}
  <div class="bg-white border border-purple-200 rounded-xl shadow-sm overflow-hidden">

    {{-- Cabecera de Tabla --}}
    <div class="hidden md:grid md:grid-cols-12 px-4 py-3 text-[11px] font-bold text-slate-600 uppercase tracking-widest border-b border-purple-200 bg-purple-50/30">
      <div class="col-span-2">CÓDIGO</div>
      <div class="col-span-4">NOMBRE</div>
      <div class="col-span-2">ZONA</div>
      <div class="col-span-2 text-center">ESTADO ACTUAL</div>
      <div class="col-span-2 text-right">ACCIONES</div>
    </div>

    {{-- Listado --}}
    <div class="divide-y divide-slate-100">
      @forelse($pickers as $picker)
        <div onclick="window.location='{{ route('pickers.show', $picker) }}'" 
             class="grid grid-cols-2 md:grid-cols-12 items-center px-4 py-3.5 hover:bg-purple-50/40 transition-colors gap-2 md:gap-0 cursor-pointer group">
          
          <div class="col-span-2 font-mono text-xs font-semibold text-slate-500 group-hover:text-slate-900 transition-colors">
            {{ $picker->employee_code ?? '—' }}
          </div>

          <div class="col-span-2 md:col-span-4">
            <div class="text-sm font-bold text-slate-900 uppercase group-hover:text-purple-700 transition-colors">{{ $picker->display_name }}</div>
            <div class="text-xs font-medium text-slate-500">{{ $picker->full_name }}</div>
          </div>

          <div class="hidden md:block col-span-2 text-xs font-semibold text-slate-600">
            {{ $picker->zone_assigned ?? 'General' }}
          </div>

          <div class="col-span-1 md:col-span-2 md:text-center">
            @if(!$picker->is_active)
              <span class="inline-block px-2.5 py-0.5 text-[10px] font-bold tracking-wider bg-rose-100 text-rose-800 border border-rose-200 rounded-md">
                INACTIVO
              </span>
            @else
              <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-[10px] font-bold tracking-wider border rounded-md shadow-sm {{ $picker->active_tasks_count > 0 ? 'bg-rose-50 text-rose-800 border-rose-200' : 'bg-emerald-50 text-emerald-800 border-emerald-200' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $picker->active_tasks_count > 0 ? 'bg-rose-600 animate-pulse' : 'bg-emerald-600' }}"></span>
                {{ $picker->active_tasks_count > 0 ? "EN PICKING ({$picker->active_tasks_count})" : 'LIBRE (0)' }}
              </span>
            @endif
          </div>

          <div class="col-span-1 md:col-span-2 flex items-center justify-end gap-2" onclick="event.stopPropagation()">
            <a href="{{ route('pickers.edit', $picker) }}" class="p-1.5 text-slate-400 hover:text-purple-700 hover:bg-purple-50 rounded-md transition-colors" title="Editar">
              <i data-lucide="edit-2" class="w-4 h-4" style="stroke-width:1.75"></i>
            </a>
            <form action="{{ route('pickers.destroy', $picker) }}" method="POST" onsubmit="return confirm('¿Seguro de dar de baja a este picker?');" class="inline">
              @csrf
              @method('DELETE')
              <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-md transition-colors cursor-pointer" title="Eliminar">
                <i data-lucide="trash-2" class="w-4 h-4" style="stroke-width:1.75"></i>
              </button>
            </form>
          </div>

        </div>
      @empty
        <div class="py-16 text-center text-slate-400 text-sm font-medium">
          No se encontraron pickers para los filtros seleccionados.
        </div>
      @endforelse
    </div>

  </div>

  @if($pickers->hasPages())
    <div class="mt-6">
      {{ $pickers->links() }}
    </div>
  @endif

@endsection