@extends('layouts.app')

@section('content')

  <div class="mb-6">
    <a href="{{ route('pickers.index') }}" class="inline-flex items-center gap-1.5 text-xs text-white/40 hover:text-white/80 mb-2 transition-colors">
      <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
      Volver al listado
    </a>
    <h2 class="text-white font-semibold text-lg">Nuevo Picker</h2>
    <p class="text-white/35 text-sm mt-1">Registra un nuevo preparador en el sistema</p>
  </div>

  <div class="bg-gray-900/40 border border-white/[0.06] rounded-xl p-6">
    <form action="{{ route('pickers.store') }}" method="POST">
      @csrf
      @include('pickers._form')
    </form>
  </div>

@endsection