<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado de Pedidos - Clientes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-screen w-screen overflow-hidden bg-slate-900 flex items-center justify-center p-[1vh]">

    <div
        x-data="pedidosApp"
        class="w-full h-full max-w-[1900px] bg-slate-900 border border-slate-700 rounded-2xl overflow-hidden shadow-2xl flex flex-col"
    >

        {{-- Título --}}
        <div class="shrink-0 bg-slate-900 border-b border-slate-700 py-[0.8vh] px-[2vw] text-center">
            <h1 class="text-amber-50 font-extrabold tracking-wide text-[2.4vh]">
                ESTADO DE PEDIDOS CLIENTES
            </h1>
        </div>

        {{-- Encabezado de columnas --}}
        <div class="shrink-0 grid grid-cols-[2.5fr_1.2fr_1.3fr_0.8fr] items-center py-[0.6vh] px-[2vw] border-b border-slate-700">
            <span class="text-slate-400 font-bold tracking-wider text-[2vh]">CLIENTE</span>
            <span class="text-slate-400 font-bold tracking-wider text-[2vh]">TICKET</span>
            <span class="text-slate-400 font-bold tracking-wider text-[2vh]">PICKER</span>
            <div class="flex justify-end gap-[0.8vw] text-slate-400 text-[1.6vh]">
                <span>&#9664;</span>
                <span>&#9654;</span>
            </div>
        </div>

        {{-- Filas dinámicas obtenidas desde la API --}}
        <div class="flex-1 flex flex-col min-h-0">
            <template x-for="(pedido, index) in pedidos" :key="pedido.ticket ?? index">
                <div
                    class="flex-1 grid grid-cols-[2.5fr_1.2fr_1.3fr_0.8fr] items-center px-[2vw]"
                    :class="index % 2 === 0 ? 'bg-slate-800/40' : 'bg-transparent'"
                >
                    <span class="text-amber-50 font-extrabold text-[3vh] truncate pr-[1vw]" x-text="pedido.cliente"></span>
                    <span class="text-slate-300 font-bold text-[3.8vh]" x-text="pedido.ticket"></span>
                    <span class="text-amber-50 font-extrabold text-[3vh] truncate pr-[1vw]" x-text="pedido.picker"></span>
                    <div class="flex justify-end">
                        <span
                            class="bg-green-600 text-white font-bold text-[1.3vh] px-[1vw] py-[0.5vh] rounded-md whitespace-nowrap"
                            x-text="badgeLabels[pedido.estado] ?? pedido.estado"
                        ></span>
                    </div>
                </div>
            </template>
        </div>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('pedidosApp', () => ({
                pedidos: [],
                badgeLabels: {
                    PEND: 'PEND.',
                    PROG: 'PROG.',
                    COMP: 'COMP.',
                },
                async fetchPedidos() {
                    try {
                        const res = await fetch('/api/ticket/latest', {
                            headers: { 'Accept': 'application/json' },
                        });
                        
                        if (!res.ok) throw new Error(`HTTP Error: ${res.status}`);
                        
                        const json = await res.json();
                        const lista = json.tickets ?? json.data ?? (Array.isArray(json) ? json : []);

                        if (Array.isArray(lista) && lista.length > 0) {
                            this.pedidos = lista.map(item => ({
                                cliente: item.customer || 'SIN CLIENTE',
                                ticket: item.ticket_number || item.id,
                                picker: item.seller || 'SIN ASIGNAR',
                                estado: item.status || 'PEND'
                            }));
                        }
                    } catch (e) {
                        console.error('Error al actualizar pedidos:', e);
                    }
                },
                init() {
                    this.fetchPedidos();
                    setInterval(() => this.fetchPedidos(), 30000);
                }
            }));
        });
    </script>

</body>
</html>