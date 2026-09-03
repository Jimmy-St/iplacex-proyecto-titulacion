@props(['items' => []])

<div class="bg-white border border-purple-200 rounded-xl shadow-sm overflow-hidden mt-6">
    {{-- GRILLA DE PRODUCTOS (CABECERA) --}}
    <div class="hidden md:grid md:grid-cols-4 px-4 py-3
                text-[11px] font-bold text-slate-600 uppercase tracking-widest
                border-b border-purple-200 bg-purple-50/30">
        <div class="col-span-2">Producto</div>
        <div class="text-center">Cantidad</div>
        <div class="text-right">Precio</div>
    </div>

    {{-- CUERPO DE LA TABLA --}}
    <div class="divide-y divide-slate-100">
        @forelse($items as $item)
            <div class="grid grid-cols-2 md:grid-cols-4 items-center px-4 py-3.5 hover:bg-purple-50/40 transition-colors">
                <div class="col-span-2 text-sm font-bold text-slate-900">{{ $item->product_name ?? '—' }}</div>
                <div class="hidden md:block text-sm font-semibold text-slate-600 text-center">{{ $item->quantity }}</div>
                <div class="text-sm font-semibold text-slate-700 text-right font-mono">${{ number_format($item->price, 0, ',', '.') }}</div>
            </div>
        @empty
            <div class="px-4 py-12 text-center text-slate-400 text-sm font-medium">Sin ítems registrados</div>
        @endforelse
    </div>
</div>