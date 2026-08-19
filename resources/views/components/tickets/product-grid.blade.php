{{-- resources/views/components/tickets/product-grid.blade.php --}}
@props(['items' => []])

<div>
    {{-- GRILLA DE PRODUCTOS (CABECERA) --}}
    <div class="hidden md:grid md:grid-cols-4 px-4 py-2.5
                text-[11px] font-semibold text-white/30 uppercase tracking-widest
                border-b border-white/[0.07] mt-4">
        <div class="col-span-2">Producto</div>
        <div class="text-center">Cantidad</div>
        <div class="text-right">Precio</div>
    </div>

    {{-- CUERPO DE LA TABLA --}}
    <div class="divide-y divide-white/[0.05]">
        @forelse($items as $item)
            <div class="grid grid-cols-2 md:grid-cols-4 items-center px-4 py-3.5 hover:bg-slate-800/20 transition-colors">
                <div class="col-span-2 text-sm text-white">{{ $item->product_name ?? '—' }}</div>
                <div class="hidden md:block text-sm text-white/55 text-center">{{ $item->quantity }}</div>
                <div class="text-sm text-white/55 text-right">${{ number_format($item->price, 0, ',', '.') }}</div>
            </div>
        @empty
            <div class="px-4 py-8 text-center text-white/25 text-sm">Sin ítems registrados</div>
        @endforelse
    </div>
</div>