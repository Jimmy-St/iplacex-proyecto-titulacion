@props(['ticket'])

<div class="flex items-center gap-2 bg-white/[0.02] border border-white/[0.06] rounded-xl px-3 py-2 mt-1">
    <i data-lucide="file-text" class="w-5 h-5 text-purple-400" style="stroke-width:1.5"></i>
    <div class="leading-none">
        <span class="text-xs font-medium text-white/80 block">{{ $ticket->document_type ?? 'Boleta' }}</span>
        <span class="text-[10px] text-white/35 font-mono block mt-1">#{{ $ticket->document_number ?? '' }}</span>
    </div>
</div>