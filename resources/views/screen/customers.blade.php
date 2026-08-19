<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado de Pedidos - Clientes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen w-screen overflow-hidden bg-slate-900 flex items-center justify-center p-[1vh]">

    <div class="w-full h-full max-w-[1900px] bg-slate-900 border border-slate-700 rounded-2xl overflow-hidden shadow-2xl flex flex-col">

        {{-- Título --}}
        <div class="shrink-0 bg-slate-900 border-b border-slate-700 py-[0.8vh] px-[2vw] text-center">
            <h1 class="text-amber-50 font-extrabold tracking-wide text-[2.4vh]">
                ESTADO DE PEDIDOS CLIENTES
            </h1>
        </div>

        {{-- Encabezado de columnas --}}
        <div class="shrink-0 grid grid-cols-[2.2fr_1.2fr_1.6fr_0.8fr] items-center py-[0.6vh] px-[2vw] border-b border-slate-700">
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
            @php
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

                $badgeClasses = [
                    'PEND' => 'bg-red-600',
                    'PROG' => 'bg-amber-500',
                    'COMP' => 'bg-green-600',
                ];

                $badgeLabels = [
                    'PEND' => 'PEND.',
                    'PROG' => 'PROG.',
                    'COMP' => 'COMP.',
                ];
            @endphp

            @foreach ($pedidos as $index => $pedido)
                <div class="flex-1 grid grid-cols-[2.2fr_1.2fr_1.6fr_0.8fr] items-center px-[2vw] {{ $index % 2 === 0 ? 'bg-slate-800/40' : 'bg-transparent' }}">
                    <span class="text-amber-50 font-extrabold text-[3vh] truncate pr-[1vw]">
                        {{ $pedido['cliente'] }}
                    </span>
                    <span class="text-slate-300 font-bold text-[3.8vh]">
                        {{ $pedido['ticket'] }}
                    </span>
                    <span class="text-amber-50 font-extrabold text-[3vh] truncate pr-[1vw]">
                        {{ $pedido['picker'] }}
                    </span>
                    <div class="flex justify-end">
                        <span class="{{ $badgeClasses[$pedido['estado']] }} text-white font-bold text-[1.3vh] px-[1vw] py-[0.5vh] rounded-md whitespace-nowrap">
                            {{ $badgeLabels[$pedido['estado']] }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

</body>
</html>