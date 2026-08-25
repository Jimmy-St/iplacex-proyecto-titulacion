<?php

use function Livewire\Volt\state;
use function Livewire\Volt\mount;

state([
    'numero' => null,
    'contador' => 0,
]);

mount(function ($numero) {
    $this->numero = $numero;
});

$incrementar = function () {
    $this->contador++;
};

$reiniciar = function () {
    $this->contador = 0;
};

?>

<div class="p-6 text-white min-h-screen bg-zinc-950">
    <h1 class="text-2xl font-bold mb-2">Demo Volt funcionando 🎉</h1>
    <p class="text-sm text-white/60 mb-6">
        Parámetro de ruta recibido: 
        <span class="text-purple-400 font-semibold">{{ $numero }}</span>
    </p>

    <div class="bg-white/5 rounded-xl p-4 max-w-xs">
        <p class="text-xs text-white/40 uppercase tracking-widest mb-2">Contador reactivo</p>
        <p class="text-4xl font-bold mb-4">{{ $contador }}</p>

        <div class="flex gap-2">
            <button wire:click="incrementar" 
                    class="bg-purple-600 px-4 py-2 rounded-lg text-xs font-medium hover:bg-purple-500">
                + Incrementar
            </button>
            <button wire:click="reiniciar" 
                    class="bg-white/10 px-4 py-2 rounded-lg text-xs font-medium hover:bg-white/20">
                Reiniciar
            </button>
        </div>
    </div>
</div>