@props(['ticket'])

<div class="mt-1">
    <div x-show="!isPaid" class="flex items-center gap-2 bg-emerald-500/5 border border-emerald-500/20 rounded-xl px-3 py-2">
        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
        <span class="text-xs font-medium text-emerald-400">Pagado</span>
    </div>
    <div x-show="isPaid" class="flex items-center gap-2 bg-rose-500/5 border border-rose-500/20 rounded-xl px-3 py-2" style="display: none;">
        <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
        <span class="text-xs font-medium text-rose-400">No Pagado</span>
    </div>
</div>