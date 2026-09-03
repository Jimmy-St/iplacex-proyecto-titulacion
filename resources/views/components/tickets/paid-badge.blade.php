@props(['ticket'])

<div class="mt-1">
    <div x-show="!isPaid" class="flex items-center gap-2 bg-emerald-50 border border-emerald-200 rounded-xl px-3 py-2 shadow-sm">
        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
        <span class="text-xs font-semibold text-emerald-800">Pagado</span>
    </div>
    <div x-show="isPaid" class="flex items-center gap-2 bg-rose-50 border border-rose-200 rounded-xl px-3 py-2 shadow-sm" style="display: none;">
        <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
        <span class="text-xs font-semibold text-rose-800">No Pagado</span>
    </div>
</div>