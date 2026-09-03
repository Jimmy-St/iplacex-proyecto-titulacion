@props(['ticket'])

<div class="flex items-center gap-2 bg-purple-50 border border-purple-200 rounded-xl px-3 py-2 mt-1 shadow-sm">
    <i data-lucide="file-text" class="w-5 h-5 text-purple-600" style="stroke-width:1.75"></i>
    <div class="leading-none">
        <span class="text-xs font-semibold text-slate-900 block">{{ $ticket->document_type ?? 'Boleta' }}</span>
        <span class="text-[10px] text-slate-500 font-mono font-bold block mt-1">#{{ $ticket->document_number ?? '' }}</span>
    </div>
</div>