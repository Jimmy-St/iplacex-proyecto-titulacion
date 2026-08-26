@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-900 rounded-xl border border-white/[0.06] text-white">
    <h1 class="text-2xl font-bold">¡Contenedor clásico funcionando!</h1>
    
    {{-- Aquí llamamos a tu componente de Livewire pasando el parámetro --}}
    @livewire('demo-show', ['numero' => $numero])
</div>
@endsection