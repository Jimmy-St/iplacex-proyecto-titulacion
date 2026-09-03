<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado de Pedidos - Clientes</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            /* Colores de Fondo y Bordes con acento morado más visible */
            --color-bg-body: #f8fafc;
            --color-bg-card: #ffffff;
            --color-bg-row-alt: rgba(233, 213, 255, 0.45); /* Interlineado moradito más notorio */
            --color-border: #c084fc; /* Líneas de borde en morado más vivo (purple-400) */

            /* Colores de Tipografía */
            --color-text-main: #0f172a;
            --color-text-muted: #64748b;
            --color-text-ticket: #334155;
            --color-text-badge: #ffffff;
            --color-title: #9333ea; /* Título principal en morado */

            /* Tipografías */
            --font-family-base: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            --font-family-badge: 'Arial Narrow', 'Franklin Gothic Medium', sans-serif;

            /* Tamaños de Fuente Relativos */
            --font-size-title: 3.2vh;
            --font-size-header: 2.4vh;
            --font-size-client: 3.5vh;
            --font-size-ticket: 4.8vh;
            --font-size-picker: 3.2vh;
            --font-size-badge: 2.2vh;

            /* Espaciados y Dimensiones */
            --body-padding: 1.5vh;
            --container-max-width: 1900px;
            --container-radius: 1rem;
            --grid-columns-layout: 2.5fr 0.8fr 1.3fr 0.5fr;
            --grid-padding-x: 2vw;
            --badge-width: 120px;
            --badge-padding-y: 0.5vh;
            --badge-radius: 0.5rem;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            width: 100vw;
            height: 100vh;
            overflow: hidden;
            background-color: var(--color-bg-body);
            font-family: var(--font-family-base);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: var(--body-padding);
        }

        .screen-container {
            width: 100%;
            height: 100%;
            max-width: var(--container-max-width);
            background-color: var(--color-bg-card);
            border: 2px solid var(--color-border);
            border-radius: var(--container-radius);
            box-shadow: 0 15px 30px -5px rgba(147, 51, 234, 0.15);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .screen-header {
            flex-shrink: 0;
            background-color: #faf5ff;
            border-bottom: 2px solid var(--color-border);
            padding: 1.2vh var(--grid-padding-x);
            text-align: center;
        }

        .screen-title {
            color: var(--color-title);
            font-size: var(--font-size-title);
            font-weight: 900;
            letter-spacing: 0.08em;
            text-shadow: 0 1px 2px rgba(147, 51, 234, 0.1);
        }

        .columns-header {
            flex-shrink: 0;
            display: grid;
            grid-template-columns: var(--grid-columns-layout);
            align-items: center;
            padding: 1vh var(--grid-padding-x);
            border-bottom: 2px solid var(--color-border);
            background-color: #f3e8ff; /* Fondo morado claro para cabecera de tabla */
        }

        .column-label {
            color: #581c87;
            font-size: var(--font-size-header);
            font-weight: 800;
            letter-spacing: 0.05em;
        }

        .column-label-status {
            text-align: center;
            width: var(--badge-width);
        }

        .rows-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 0;
            gap: 0.8vh;
            padding: 1vh 0;
        }

        .order-row {
            flex: 1;
            display: grid;
            grid-template-columns: var(--grid-columns-layout);
            align-items: center;
            padding: 0 var(--grid-padding-x);
            background-color: transparent;
            border-bottom: 1.5px solid var(--color-border);
        }

        .order-row-alt {
            background-color: var(--color-bg-row-alt);
        }

        .cell-client {
            color: var(--color-text-main);
            font-size: var(--font-size-client);
            font-weight: 800;
            text-transform: uppercase;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            padding-right: 1vw;
        }

        .cell-ticket {
            color: var(--color-text-ticket);
            font-size: var(--font-size-ticket);
            font-weight: 800;
            font-family: monospace;
        }

        .cell-picker {
            color: var(--color-text-main);
            font-size: var(--font-size-picker);
            font-weight: 700;
            text-transform: uppercase;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            padding-right: 1vw;
        }

        .cell-status-container {
            display: flex;
            justify-content: flex-end;
        }

        /* Widgets/Badges con estilo de botón degradado y bordes sólidos notorios */
        .status-badge {
            font-family: var(--font-family-badge);
            color: var(--color-text-badge);
            font-size: var(--font-size-badge);
            font-weight: 900;
            text-transform: uppercase;
            white-space: nowrap;
            letter-spacing: 0.05em;
            width: var(--badge-width);
            padding: var(--badge-padding-y) 0;
            border-radius: var(--badge-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.3), 0 3px 6px rgba(0, 0, 0, 0.12);
        }

        .badge-pending {
            background: linear-gradient(135deg, #fbbf24, #d97706);
            border: 2px solid #b45309;
        }

        .badge-progress {
            background: linear-gradient(135deg, #60a5fa, #2563eb);
            border: 2px solid #1d4ed8;
        }

        .badge-pagar {
            background: linear-gradient(135deg, #34d399, #059669);
            border: 2px solid #047857;
        }
    </style>
</head>
<body>
    <div x-data="pedidosApp" class="screen-container">
        {{-- Título --}}
        <header class="screen-header">
            <h1 class="screen-title">ESTADO DE PEDIDOS CLIENTES</h1>
        </header>
        
        {{-- Encabezado de columnas --}}
        <div class="columns-header">
            <span class="column-label">CLIENTE</span>
            <span class="column-label">TICKET</span>
            <span class="column-label">PICKER</span>
            <div class="cell-status-container">
                <span class="column-label column-label-status">ESTADO</span>
            </div>
        </div>

        {{-- Filas dinámicas obtenidas desde la API --}}
        <div class="rows-container">
            <template x-for="(pedido, index) in pedidos" :key="pedido.ticket ?? index">
                <div class="order-row" :class="{ 'order-row-alt': index % 2 === 0 }">
                    <span class="cell-client" x-text="pedido.cliente"></span>
                    <span class="cell-ticket" x-text="pedido.ticket"></span>
                    <span class="cell-picker" x-text="pedido.picker"></span>
                    <div class="cell-status-container">
                        <span
                            class="status-badge"
                            :class="getStatusConfig(pedido.estado).class"
                            x-text="getStatusConfig(pedido.estado).label"
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
                statusMap: {
                    'pending': { label: 'PEND.', class: 'badge-pending' },
                    'pendiente': { label: 'PEND.', class: 'badge-pending' },
                    'pend': { label: 'PEND.', class: 'badge-pending' },
                    
                    'in_progress': { label: 'PREP.', class: 'badge-progress' },
                    'preparando': { label: 'PREP.', class: 'badge-progress' },
                    'prog': { label: 'PREP.', class: 'badge-progress' },
                    
                    'completed': { label: 'PAGAR', class: 'badge-pagar' },
                    'completado': { label: 'PAGAR', class: 'badge-pagar' },
                    'comp': { label: 'PAGAR', class: 'badge-pagar' }
                },
                getStatusConfig(status) {
                    const key = String(status || '').toLowerCase().trim();
                    return this.statusMap[key] || { 
                        label: (status || 'PEND.').toUpperCase(), 
                        class: 'badge-pending' 
                    };
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
                                picker: item.picker || item.seller || 'SIN ASIGNAR',
                                estado: item.status || 'pending'
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