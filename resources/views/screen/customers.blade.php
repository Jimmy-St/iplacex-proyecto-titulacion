<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado de Pedidos - Clientes</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            /* Colores de Fondo y Bordes */
            --color-bg-body: #0f172a;
            --color-bg-card: #0f172a;
            --color-bg-row-alt: rgba(30, 41, 59, 0.4);
            --color-border: #334155;

            /* Colores de Tipografía */
            --color-text-main: #fffbeb;
            --color-text-muted: #94a3b8;
            --color-text-ticket: #cbd5e1;
            --color-text-badge: #ffffff;

            /* Colores de Estado */
            --color-status-pending: #E8890C;
            --color-status-progress: #2255D6;
            --color-status-completed: #0F9D58;

            /* Tipografías */
            --font-family-base: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            --font-family-badge: 'Arial Narrow', 'Franklin Gothic Medium', sans-serif;

            /* Tamaños de Fuente Relativos */
            --font-size-title: 3vh;
            --font-size-header: 2.4vh;
            --font-size-client: 3.5vh;
            --font-size-ticket: 4.8vh;
            --font-size-picker: 3.2vh;
            --font-size-badge: 2.6vh;

            /* Espaciados y Dimensiones */
            --body-padding: 1vh;
            --container-max-width: 1900px;
            --container-radius: 1rem;
            --grid-columns-layout: 2.5fr 0.8fr 1.3fr 0.5fr;
            --grid-padding-x: 2vw;
            --badge-width: 140px;
            --badge-padding-y: 0.4vh;
            --badge-radius: 0.375rem;
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
            border: 1px solid var(--color-border);
            border-radius: var(--container-radius);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .screen-header {
            flex-shrink: 0;
            background-color: var(--color-bg-card);
            border-bottom: 1px solid var(--color-border);
            padding: 0.4vh var(--grid-padding-x);
            text-align: center;
        }

        .screen-title {
            color: var(--color-text-main);
            font-size: var(--font-size-title);
            font-weight: 800;
            letter-spacing: 0.05em;
        }

        .columns-header {
            flex-shrink: 0;
            display: grid;
            grid-template-columns: var(--grid-columns-layout);
            align-items: center;
            padding: 0.6vh var(--grid-padding-x);
            border-bottom: 1px solid var(--color-border);
        }

        .column-label {
            color: var(--color-text-muted);
            font-size: var(--font-size-header);
            font-weight: 700;
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
        }

        .order-row {
            flex: 1;
            display: grid;
            grid-template-columns: var(--grid-columns-layout);
            align-items: center;
            padding: 0 var(--grid-padding-x);
            background-color: transparent;
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
            font-weight: 700;
        }

        .cell-picker {
            color: var(--color-text-main);
            font-size: var(--font-size-picker);
            font-weight: 800;
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
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .badge-pending {
            background-color: var(--color-status-pending);
        }

        .badge-progress {
            background-color: var(--color-status-progress);
        }

        .badge-completed {
            background-color: var(--color-status-completed);
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
                    'pending': { label: 'PENDIENTE', class: 'badge-pending' },
                    'pendiente': { label: 'PENDIENTE', class: 'badge-pending' },
                    'pend': { label: 'PENDIENTE', class: 'badge-pending' },
                    
                    'in_progress': { label: 'PREPARANDO', class: 'badge-progress' },
                    'preparando': { label: 'PREPARANDO', class: 'badge-progress' },
                    'prog': { label: 'PREPARANDO', class: 'badge-progress' },
                    
                    'completed': { label: 'COMPLETADO', class: 'badge-completed' },
                    'completado': { label: 'COMPLETADO', class: 'badge-completed' },
                    'comp': { label: 'COMPLETADO', class: 'badge-completed' }
                },
                getStatusConfig(status) {
                    const key = String(status || '').toLowerCase().trim();
                    return this.statusMap[key] || { 
                        label: (status || 'PENDIENTE').toUpperCase(), 
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