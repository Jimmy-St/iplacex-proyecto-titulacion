@extends('layouts.app')

@section('content')

  <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
      <a href="{{ route('pickers.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-900 mb-2 transition-colors">
        <i data-lucide="arrow-left" class="w-3.5 h-3.5" style="stroke-width:1.75"></i>
        Volver al listado
      </a>
      <div class="flex flex-wrap items-center gap-3">
        <h2 class="text-slate-900 font-bold text-xl">{{ $picker->display_name }}</h2>
        
        @if(!$picker->is_active)
          <span class="px-2.5 py-0.5 text-[10px] font-bold tracking-wider bg-rose-100 text-rose-800 border border-rose-200 rounded-md">
            INACTIVO
          </span>
        @else
          <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-[10px] font-bold tracking-wider border rounded-md shadow-sm {{ $picker->active_tasks_count > 0 ? 'bg-rose-50 text-rose-800 border-rose-200' : 'bg-emerald-50 text-emerald-800 border-emerald-200' }}">
            <span class="w-1.5 h-1.5 rounded-full {{ $picker->active_tasks_count > 0 ? 'bg-rose-600 animate-pulse' : 'bg-emerald-600' }}"></span>
            {{ $picker->active_tasks_count > 0 ? "EN PICKING ({$picker->active_tasks_count})" : 'LIBRE (0)' }}
          </span>
        @endif
      </div>
      <p class="text-slate-600 text-xs mt-1 font-medium">{{ $picker->full_name }} &bull; {{ $picker->employee_code ?? 'Sin código' }}</p>
    </div>

    <div class="flex items-center gap-2">

      <!-- Componente Alpine.js para Presencia -->
      <div x-data="{ presente: true }">
          <button @click="presente = !presente"
              :class="presente 
                  ? 'bg-emerald-50 border-emerald-300 text-emerald-800 shadow-sm' 
                  : 'bg-white border-purple-200 text-slate-700 hover:text-slate-900 hover:border-purple-300 shadow-sm'"
              class="w-36 h-[34px] inline-flex items-center justify-center gap-2 px-3 border rounded-lg text-xs font-semibold transition-all cursor-pointer">
              
              <i data-lucide="user-check" class="w-3.5 h-3.5 shrink-0 text-emerald-600" x-show="presente" style="stroke-width:1.75"></i>
              <i data-lucide="user-x" class="w-3.5 h-3.5 shrink-0 text-slate-400" x-show="!presente" style="stroke-width:1.75"></i>
              
              <span class="w-20 text-center truncate" x-text="presente ? 'Presente' : 'Ausente'"></span>
          </button>
      </div>
      
      <!-- Componente Colación -->
      @livewire('picker-lunch-button', ['picker' => $picker])

      <a href="{{ route('pickers.edit', $picker) }}" 
         class="inline-flex items-center gap-2 px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 hover:text-slate-900 text-xs font-semibold rounded-lg transition-colors border border-purple-200 shadow-sm">
        <i data-lucide="edit-2" class="w-3.5 h-3.5" style="stroke-width:1.75"></i>
        Editar Ficha
      </a>
    </div>
  </div>

  {{-- Tarjetas de Resumen Rápido --}}
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white border border-purple-200 rounded-xl p-4 shadow-sm">
      <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Zona Asignada</span>
      <div class="text-base font-bold text-slate-900 mt-1">{{ $picker->zone_assigned ?? 'General' }}</div>
    </div>
    <div class="bg-white border border-purple-200 rounded-xl p-4 shadow-sm">
      <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tareas Activas</span>
      <div class="text-base font-bold {{ $picker->active_tasks_count > 0 ? 'text-rose-600' : 'text-emerald-600' }} mt-1">
        {{ $picker->active_tasks_count }} en curso
      </div>
    </div>
    <div class="bg-white border border-purple-200 rounded-xl p-4 shadow-sm">
      <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Registrado el</span>
      <div class="text-base font-bold text-slate-900 mt-1">{{ $picker->created_at ? $picker->created_at->format('d/m/Y') : '—' }}</div>
    </div>
  </div>

  {{-- Tarjeta de Historial de Tareas & Tickets --}}
  <div class="bg-white border border-purple-200 rounded-xl p-6 shadow-sm">
    <div class="flex items-center justify-between pb-4 border-b border-purple-100 mb-6">
      <div>
        <h3 class="text-slate-900 font-bold text-sm">Tareas & Tickets Asignados</h3>
        <p class="text-slate-600 text-xs mt-0.5 font-medium">Historial y actividad actual en el ERP Bicom</p>
      </div>
      <span class="text-xs font-mono font-bold {{ $picker->active_tasks_count > 0 ? 'text-rose-600' : 'text-slate-500' }}">
        {{ $picker->active_tasks_count }} activas
      </span>
    </div>

    @if($assignedTasks->count() > 0)
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="border-b border-purple-100 text-[11px] font-bold text-slate-600 uppercase tracking-widest bg-purple-50/30">
              <th class="py-3 px-3">Nro Ticket</th>
              <th class="py-3 px-3">Fecha Asignación</th>
              <th class="py-3 px-3">Estado Tarea</th>
              <th class="py-3 px-3 text-right">Acción</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 text-xs font-medium">
            @foreach($assignedTasks as $task)
              <tr class="hover:bg-purple-50/40 transition-colors">
                <td class="py-3.5 px-3 font-bold text-slate-900">
                  @if($task->ticket)
                    <a href="{{ route('tickets.show', $task->ticket->ticket_number) }}" class="text-purple-700 hover:underline">
                      #{{ $task->ticket->ticket_number }}
                    </a>
                  @else
                    #—
                  @endif
                </td>
                <td class="py-3.5 px-3 text-slate-600 font-mono">
                  {{ $task->created_at ? $task->created_at->format('d-m-Y H:i') : '—' }}
                </td>
                <td class="py-3.5 px-3">
                  @if($task->status === 'COMPLETADO')
                    <span class="inline-block px-2 py-0.5 text-[10px] font-bold tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-200 rounded-md">
                      COMPLETADO
                    </span>
                  @elseif($task->status === 'PREPARANDO')
                    <span class="inline-block px-2 py-0.5 text-[10px] font-bold tracking-wider bg-amber-100 text-amber-800 border border-amber-200 rounded-md">
                      PREPARANDO
                    </span>
                  @else
                    <span class="inline-block px-2 py-0.5 text-[10px] font-bold tracking-wider bg-purple-100 text-purple-800 border border-purple-200 rounded-md">
                      PENDIENTE
                    </span>
                  @endif
                </td>
                <td class="py-3.5 px-3 text-right">
                  @if($task->ticket)
                    <a href="{{ route('tickets.show', $task->ticket->ticket_number) }}" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 hover:text-slate-900 rounded-lg transition-colors border border-slate-200 shadow-sm font-semibold">
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
      <div class="py-16 text-center text-slate-400 text-xs font-medium">
        <i data-lucide="package-search" class="w-8 h-8 mx-auto mb-2 opacity-50 text-purple-600" style="stroke-width:1.5"></i>
        No hay tareas ni tickets asignados en el historial de este picker.
      </div>
    @endif
  </div>

@endsection