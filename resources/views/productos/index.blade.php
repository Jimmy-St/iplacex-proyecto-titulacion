@extends('layouts.app')

@section('content')

  <div class="mb-6">
    <h2 class="text-white font-semibold text-lg">Productos</h2>
    <p class="text-white/35 text-sm mt-1">Catálogo de productos</p>
  </div>

  <div class="hidden md:grid md:grid-cols-4 px-4 py-2.5
              text-[11px] font-semibold text-white/30 uppercase tracking-widest
              border-b border-white/[0.07]">
    <div>Código</div>
    <div>Descripción</div>
    <div>Stock</div>
    <div class="text-center">Estado</div>
  </div>

  <div class="divide-y divide-white/[0.05]">
    {{-- @foreach($productos as $producto) --}}
    <div class="grid grid-cols-2 md:grid-cols-4 items-center px-4 py-4 hover:bg-slate-800/20 transition-colors">
      <div class="text-sm font-bold text-white">PRD-001</div>
      <div class="text-sm text-white/55">Producto de ejemplo</div>
      <div class="hidden md:block text-sm text-white/55">42</div>
      <div class="text-right md:text-center">
        <span class="inline-block px-2.5 py-0.5 text-[11px] font-black tracking-wider
                     bg-green-500/15 text-green-400 border border-green-500/25 rounded-md">
          OK
        </span>
      </div>
    </div>
    {{-- @endforeach --}}
  </div>

@endsection