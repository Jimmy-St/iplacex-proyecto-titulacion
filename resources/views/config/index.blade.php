@extends('layouts.app')

@section('content')

  <div class="mb-6">
    <h2 class="text-white font-semibold text-lg">Configuración</h2>
    <p class="text-white/35 text-sm mt-1">Ajustes del sistema</p>
  </div>

  <div class="flex flex-col gap-3 max-w-lg">

    <div class="bg-gray-900 border border-white/[0.06] rounded-xl px-5 py-4 flex items-center justify-between">
      <div>
        <p class="text-sm font-medium text-white">Nombre del sistema</p>
        <p class="text-xs text-white/35 mt-0.5">Identificador visible en el header</p>
      </div>
      <input
        type="text"
        value="Gestión"
        class="bg-gray-950 border border-white/10 rounded-lg px-3 py-1.5
               text-white text-sm outline-none focus:border-purple-500 w-36 transition-colors"
      >
    </div>

    <div class="bg-gray-900 border border-white/[0.06] rounded-xl px-5 py-4 flex items-center justify-between">
      <div>
        <p class="text-sm font-medium text-white">Usuario activo</p>
        <p class="text-xs text-white/35 mt-0.5">Rol en sesión</p>
      </div>
      <span class="text-sm text-white/55">Admin / Supervisor</span>
    </div>

  </div>

@endsection