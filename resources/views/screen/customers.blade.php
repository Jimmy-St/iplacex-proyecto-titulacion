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

    @php
        // Datos iniciales (fallback mientras carga el primer fetch a /api/latest)
        $pedidos = [
            ['cliente' => 'MARIO CARMONA', 'ticket' => '78676323', 'picker' => 'M. GONZALEZ', 'estado' => 'PEND'],
            ['cliente' => 'MARIA G.',      'ticket' => '78676324', 'picker' => 'P. GARCIA',    'estado' => 'PEND'],
            ['cliente' => 'ALEX SOTO',     'ticket' => '78676425', 'picker' => 'M. LENETA',    'estado' => 'PROG'],
            ['cliente' => 'PEDRO R.',      'ticket' => '78673426', 'picker' => 'F. CLAROZ',    'estado' => 'COMP'],
            ['cliente' => 'LUISA M.',      'ticket' => '78673427', 'picker' => 'J. RAMIREZ',   'estado' => 'PEND'],
            ['cliente' => 'DAVID J.',      'ticket' => '78663428', 'picker' => 'S. LOPEZ',     'estado' => 'PROG'],
            ['cliente' => 'CARMEN O.',     'ticket' => '78763429', 'picker' => 'M. GONZALEZ',  'estado' => 'COMP'],
            ['cliente' => 'LUIS V.',       'ticket' => '76763430', 'picker' => 'P. GARCIA',    'estado' => 'PEND'],
            ['cliente' => 'SOFIA K.',      'ticket' => '86763431', 'picker' => 'M. LENETA',    'estado' => 'COMP'],
            ['cliente' => 'ROBERTO N.',    'ticket' => '78763432', 'picker' => 'J. RAMIREZ',   'estado' => 'PROG'],
            ['cliente' => 'ANDREA P.',     'ticket' => '78763433', 'picker' => 'S. LOPEZ',     'estado' => 'PEND'],
            ['cliente' => 'FELIPE T.',     'ticket' => '78763434', 'picker' => 'F. CLAROZ',    'estado' => 'COMP'],
        ];

        $badgeLabels = [
            'PEND' => 'PEND.',
            'PROG' => 'PROG.',
            'COMP' => 'COMP.',
        ];
    @endphp

    <div
        x-data="pedidosApp(@json($pedidos))"
        x-init="init()"
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

        {{-- Filas: se reparten el alto restante entre las 12, siempre exacto --}}
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
        function pedidosApp(initialPedidos) {
            return {
                pedidos: initialPedidos,
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
                        if (!res.ok) throw new Error('Respuesta no OK: ' + res.status);
                        const json = await res.json();
                        // Soporta tanto array plano [...] como { data: [...] }
                        const nuevos = Array.isArray(json) ? json : (json.data ?? []);
                        if (Array.isArray(nuevos) && nuevos.length > 0) {
                            this.pedidos = nuevos;
                        }
                    } catch (e) {
                        console.error('Error al actualizar pedidos:', e);
                    }
                },
                init() {
                    this.fetchPedidos();
                    setInterval(() => this.fetchPedidos(), 30000);
                },
            };
        }
    </script>

</body>
</html>