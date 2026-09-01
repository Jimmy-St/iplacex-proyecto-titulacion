@extends('layouts.app')

@section('content')

  <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
      <a href="{{ route('pickers.index') }}" class="inline-flex items-center gap-1.5 text-xs text-white/40 hover:text-white/80 mb-2 transition-colors">
        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
        Volver al listado
      </a>
      <div class="flex flex-wrap items-center gap-3">
        <h2 class="text-white font-semibold text-xl">{{ $picker->display_name }}</h2>
        
        @if(!$picker->is_active)
          <span class="px-2.5 py-0.5 text-[10px] font-bold tracking-wider bg-red-500/15 text-red-400 border border-red-500/25 rounded-md">
            INACTIVO
          </span>
        @else
          <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-[10px] font-bold tracking-wider border rounded-md {{ $picker->active_tasks_count > 0 ? 'bg-rose-500/15 text-rose-400 border-rose-500/25' : 'bg-emerald-500/15 text-emerald-400 border-emerald-500/25' }}">
            <span class="w-1.5 h-1.5 rounded-full {{ $picker->active_tasks_count > 0 ? 'bg-rose-400 animate-pulse' : 'bg-emerald-400' }}"></span>
            {{ $picker->active_tasks_count > 0 ? "EN PICKING ({$picker->active_tasks_count})" : 'LIBRE (0)' }}
          </span>
        @endif
      </div>
      <p class="text-white/35 text-xs mt-1">{{ $picker->full_name }} &bull; {{ $picker->employee_code ?? 'Sin código' }}</p>
    </div>

    <div class="flex items-center gap-2">

      <!-- Componente Alpine.js para Presencia -->
      <div x-data="{ presente: true }">
          <button @click="presente = !presente"
              :class="presente 
                  ? 'bg-emerald-500/20 border-emerald-500/60 text-emerald-300 shadow-[0_0_15px_rgba(16,185,129,0.25)]' 
                  : 'bg-slate-800 border-white/[0.08] text-white/70 hover:text-white hover:bg-slate-700'"
              class="w-36 h-[34px] inline-flex items-center justify-center gap-2 px-3 border rounded-lg text-xs font-medium transition-all">
              
              <i data-lucide="user-check" class="w-3.5 h-3.5 shrink-0 text-emerald-400" x-show="presente"></i>
              <i data-lucide="user-x" class="w-3.5 h-3.5 shrink-0 text-white/50" x-show="!presente"></i>
              
              <span class="w-20 text-center truncate" x-text="presente ? 'Presente' : 'Ausente'"></span>
          </button>
      </div>
      
      <!-- Componente Colación -->
      @livewire('picker-lunch-button', ['picker' => $picker])

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
      <span class="text-xs text-white/40 font-medium">Tareas Activas</span>
      <div class="text-base font-bold {{ $picker->active_tasks_count > 0 ? 'text-rose-400' : 'text-emerald-400' }} mt-1">
        {{ $picker->active_tasks_count }} en curso
      </div>
    </div>
    <div class="bg-gray-900/60 border border-white/[0.06] rounded-xl p-4">
      <span class="text-xs text-white/40 font-medium">Registrado el</span>
      <div class="text-base font-bold text-white mt-1">{{ $picker->created_at ? $picker->created_at->format('d/m/Y') : '—' }}</div>
    </div>
  </div>

  {{-- Tarjeta de Historial de Tareas & Tickets --}}
  <div class="bg-gray-900/40 border border-white/[0.06] rounded-xl p-6">
    <div class="flex items-center justify-between pb-4 border-b border-white/[0.06] mb-6">
      <div>
        <h3 class="text-white font-semibold text-sm">Tareas & Tickets Asignados</h3>
        <p class="text-white/35 text-xs mt-0.5">Historial y actividad actual en el ERP Bicom</p>
      </div>
      <span class="text-xs font-mono {{ $picker->active_tasks_count > 0 ? 'text-rose-400' : 'text-white/40' }}">
        {{ $picker->active_tasks_count }} activas
      </span>
    </div>

    @if($assignedTasks->count() > 0)
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="border-b border-white/[0.06] text-[11px] font-semibold text-white/30 uppercase tracking-widest">
              <th class="py-2.5 px-3">Nro Ticket</th>
              <th class="py-2.5 px-3">Fecha Asignación</th>
              <th class="py-2.5 px-3">Estado Tarea</th>
              <th class="py-2.5 px-3 text-right">Acción</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-white/[0.04] text-xs">
            @foreach($assignedTasks as $task)
              <tr class="hover:bg-slate-800/30 transition-colors">
                <td class="py-3 px-3 font-semibold text-white">
                  @if($task->ticket)
                    <a href="{{ route('tickets.show', $task->ticket->ticket_number) }}" class="text-purple-400 hover:underline">
                      #{{ $task->ticket->ticket_number }}
                    </a>
                  @else
                    #—
                  @endif
                </td>
                <td class="py-3 px-3 text-white/60 font-mono">
                  {{ $task->created_at ? $task->created_at->format('d-m-Y H:i') : '—' }}
                </td>
                <td class="py-3 px-3">
                  @if($task->status === 'COMPLETADO')
                    <span class="inline-block px-2 py-0.5 text-[10px] font-bold tracking-wider bg-emerald-500/15 text-emerald-400 border border-emerald-500/25 rounded-md">
                      COMPLETADO
                    </span>
                  @elseif($task->status === 'PREPARANDO')
                    <span class="inline-block px-2 py-0.5 text-[10px] font-bold tracking-wider bg-amber-500/15 text-amber-400 border border-amber-500/25 rounded-md">
                      PREPARANDO
                    </span>
                  @else
                    <span class="inline-block px-2 py-0.5 text-[10px] font-bold tracking-wider bg-purple-500/15 text-purple-400 border border-purple-500/25 rounded-md">
                      PENDIENTE
                    </span>
                  @endif
                </td>
                <td class="py-3 px-3 text-right">
                  @if($task->ticket)
                    <a href="{{ route('tickets.show', $task->ticket->ticket_number) }}" class="px-2.5 py-1 bg-white/[0.04] hover:bg-white/[0.08] text-white/70 hover:text-white rounded-lg transition-colors border border-white/[0.06]">
                      Ver Ticket
                    </a>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="mt-6">
        {{ $assignedTasks->links() }}
      </div>
    @else
      <div class="py-12 text-center text-white/30 text-xs">
        <i data-lucide="package-search" class="w-8 h-8 mx-auto mb-2 opacity-40"></i>
        No hay tareas ni tickets asignados en el historial de este picker.
      </div>
    @endif
  </div>

@endsection