@extends('layouts.app')

@section('content')

  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
      <h2 class="text-white font-semibold text-lg">Pickers</h2>
      <p class="text-white/35 text-sm mt-1">Gestión de preparadores de pedidos</p>
    </div>
    <a href="{{ route('pickers.create') }}" 
       class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-500 text-white text-sm font-medium rounded-lg transition-colors shrink-0">
      <i data-lucide="user-plus" class="w-4 h-4"></i>
      Nuevo Picker
    </a>
  </div>

  @if(session('success'))
    <div class="mb-4 px-4 py-3 bg-green-500/10 border border-green-500/20 text-green-400 text-sm rounded-lg flex items-center gap-2">
      <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>
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
        <i data-lucide="search" class="w-4 h-4 text-white/30 absolute left-3 top-1/2 -translate-y-1/2"></i>
        <input type="text" name="search" value="{{ $search ?? '' }}" 
               placeholder="Buscar por nombre, código o zona..." 
               class="w-full pl-9 pr-3 py-2 bg-gray-900 border border-white/[0.08] rounded-lg text-sm text-white placeholder-white/30 focus:outline-none focus:border-purple-500/50">
      </div>
      @if(!empty($search))
        <a href="{{ route('pickers.index', ['estado' => request('estado')]) }}" class="text-xs text-white/40 hover:text-white/70 underline shrink-0">Limpiar</a>
      @endif
    </form>

    {{-- Filtros Rápidos por Estado de Picker --}}
    <div class="flex items-center gap-1.5 ml-auto">
      <a href="{{ route('pickers.index', ['search' => request('search')]) }}"
         class="px-3 h-9 inline-flex items-center text-xs font-medium rounded-lg border transition-colors {{ !request('estado') ? 'bg-purple-600/20 border-purple-500/50 text-purple-300' : 'bg-gray-900 border-white/10 text-white/50 hover:text-white' }}">
        Todos
      </a>
      <a href="{{ route('pickers.index', ['search' => request('search'), 'estado' => 'disponibles']) }}"
         class="px-3 h-9 inline-flex items-center text-xs font-medium rounded-lg border transition-colors {{ request('estado') === 'disponibles' ? 'bg-emerald-500/20 border-emerald-500/50 text-emerald-300' : 'bg-gray-900 border-white/10 text-white/50 hover:text-white' }}">
        Disponibles
      </a>
      <a href="{{ route('pickers.index', ['search' => request('search'), 'estado' => 'picking']) }}"
         class="px-3 h-9 inline-flex items-center text-xs font-medium rounded-lg border transition-colors {{ request('estado') === 'picking' ? 'bg-rose-500/20 border-rose-500/50 text-rose-300' : 'bg-gray-900 border-white/10 text-white/50 hover:text-white' }}">
        Picking
      </a>
    </div>
  </div>

  {{-- Cabecera de Tabla --}}
  <div class="hidden md:grid md:grid-cols-12 px-4 py-2.5 text-[11px] font-semibold text-white/30 uppercase tracking-widest border-b border-white/[0.07]">
    <div class="col-span-2">CÓDIGO</div>
    <div class="col-span-4">NOMBRE</div>
    <div class="col-span-2">ZONA</div>
    <div class="col-span-2 text-center">ESTADO ACTUAL</div>
    <div class="col-span-2 text-right">ACCIONES</div>
  </div>

  {{-- Listado --}}
  <div class="divide-y divide-white/[0.05]">
    @forelse($pickers as $picker)
      <div onclick="window.location='{{ route('pickers.show', $picker) }}'" 
           class="grid grid-cols-2 md:grid-cols-12 items-center px-4 py-3.5 hover:bg-slate-800/30 transition-colors gap-2 md:gap-0 cursor-pointer group">
        
        <div class="col-span-2 font-mono text-xs text-white/40 group-hover:text-white/60 transition-colors">
          {{ $picker->employee_code ?? '—' }}
        </div>

        <div class="col-span-2 md:col-span-4">
          <div class="text-sm font-bold text-white uppercase group-hover:text-purple-300 transition-colors">{{ $picker->display_name }}</div>
          <div class="text-xs text-white/30">{{ $picker->full_name }}</div>
        </div>

        <div class="hidden md:block col-span-2 text-xs text-white/50">
          {{ $picker->zone_assigned ?? 'General' }}
        </div>

        <div class="col-span-1 md:col-span-2 md:text-center">
          @if(!$picker->is_active)
            <span class="inline-block px-2.5 py-0.5 text-[10px] font-bold tracking-wider bg-red-500/15 text-red-400 border border-red-500/25 rounded-md">
              INACTIVO
            </span>
          @else
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-[10px] font-bold tracking-wider border rounded-md {{ $picker->active_tasks_count > 0 ? 'bg-rose-500/15 text-rose-400 border-rose-500/25' : 'bg-emerald-500/15 text-emerald-400 border-emerald-500/25' }}">
              <span class="w-1.5 h-1.5 rounded-full {{ $picker->active_tasks_count > 0 ? 'bg-rose-400 animate-pulse' : 'bg-emerald-400' }}"></span>
              {{ $picker->active_tasks_count > 0 ? "EN PICKING ({$picker->active_tasks_count})" : 'LIBRE (0)' }}
            </span>
          @endif
        </div>

        <div class="col-span-1 md:col-span-2 flex items-center justify-end gap-2" onclick="event.stopPropagation()">
          <a href="{{ route('pickers.edit', $picker) }}" class="p-1.5 text-white/40 hover:text-purple-400 hover:bg-white/[0.04] rounded-md transition-colors" title="Editar">
            <i data-lucide="edit-2" class="w-4 h-4"></i>
          </a>
          <form action="{{ route('pickers.destroy', $picker) }}" method="POST" onsubmit="return confirm('¿Seguro de dar de baja a este picker?');" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="p-1.5 text-white/40 hover:text-red-400 hover:bg-white/[0.04] rounded-md transition-colors" title="Eliminar">
              <i data-lucide="trash-2" class="w-4 h-4"></i>
            </button>
          </form>
        </div>

      </div>
    @empty
      <div class="py-12 text-center text-white/30 text-sm">
        No se encontraron pickers para los filtros seleccionados.
      </div>
    @endforelse
  </div>

  @if($pickers->hasPages())
    <div class="mt-6">
      {{ $pickers->links() }}
    </div>
  @endif

@endsection