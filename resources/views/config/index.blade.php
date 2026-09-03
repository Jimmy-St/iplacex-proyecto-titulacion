@extends('layouts.app')

@section('content')

  <div class="mb-6">
    <h2 class="text-slate-900 font-bold text-lg">Configuración</h2>
    <p class="text-slate-600 text-sm mt-1 font-medium">Ajustes del sistema</p>
  </div>

  <div class="flex flex-col gap-3 max-w-lg">

    <div class="bg-white border border-purple-200 rounded-xl px-5 py-4 flex items-center justify-between shadow-sm">
      <div>
        <p class="text-sm font-bold text-slate-900">Nombre del sistema</p>
        <p class="text-xs text-slate-500 mt-0.5 font-medium">Identificador visible en el header</p>
      </div>
      <input
        type="text"
        value="Gestión"
        class="bg-slate-50 border border-purple-200 rounded-lg px-3 py-1.5
               text-slate-900 font-semibold text-sm outline-none focus:border-purple-600 focus:bg-white w-36 transition-colors shadow-sm"
      >
    </div>

    <div class="bg-white border border-purple-200 rounded-xl px-5 py-4 flex items-center justify-between shadow-sm">
      <div>
        <p class="text-sm font-bold text-slate-900">Usuario activo</p>
        <p class="text-xs text-slate-500 mt-0.5 font-medium">Rol en sesión</p>
      </div>
      <span class="text-sm font-semibold text-slate-700">Admin / Supervisor</span>
    </div>

  </div>

@endsection