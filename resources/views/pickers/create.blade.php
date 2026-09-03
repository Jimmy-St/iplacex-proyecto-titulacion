@extends('layouts.app')

@section('content')

  <div class="mb-6">
    <a href="{{ route('pickers.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-900 mb-2 transition-colors">
      <i data-lucide="arrow-left" class="w-3.5 h-3.5" style="stroke-width:1.75"></i>
      Volver al listado
    </a>
    <h2 class="text-slate-900 font-bold text-lg">Nuevo Picker</h2>
    <p class="text-slate-600 text-sm mt-1 font-medium">Registra un nuevo preparador en el sistema</p>
  </div>

  <div class="bg-white border border-purple-200 rounded-xl p-6 shadow-sm">
    <form action="{{ route('pickers.store') }}" method="POST">
      @csrf
      @include('pickers._form')
    </form>
  </div>

@endsection