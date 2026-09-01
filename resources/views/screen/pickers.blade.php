<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado de Pickers - Bodega</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            --color-bg-body: #0f172a;
            --color-bg-card: #0f172a;
            --color-bg-row-alt: rgba(30, 41, 59, 0.4);
            --color-border: #334155;

            --color-text-main: #fffbeb;
            --color-text-muted: #94a3b8;
            --color-text-ticket: #cbd5e1;
            --color-text-badge: #ffffff;

            --color-status-pending: #dc2626;  /* Rojo para PEND. */
            --color-status-progress: #d97706; /* Amarillo/Naranja para PROG. */
            --color-status-completed: #059669;/* Verde para COMP. */

            --font-family-base: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --font-family-badge: 'Arial Narrow', 'Franklin Gothic Medium', sans-serif;

            --font-size-title: 2.8vh;
            --font-size-header: 2.2vh;
            --font-size-client: 3.1vh;
            --font-size-ticket: 3.5vh;
            --font-size-picker: 3.1vh;
            --font-size-badge: 2.2vh;

            --body-padding: 1vh;
            --container-max-width: 1900px;
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
            border-radius: 1rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            display: grid;
            grid-template-columns: 2.2fr 1fr;
            grid-template-rows: auto auto 1fr;
            grid-template-areas:
                "header-left header-right"
                "columns-left header-right"
                "content-left content-right";
            overflow: hidden;
        }

        /* Panel Izquierdo */
        .left-panel {
            grid-area: content-left;
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--color-border);
            min-height: 0;
        }

        .screen-header-left {
            grid-area: header-left;
            border-bottom: 1px solid var(--color-border);
            border-right: 1px solid var(--color-border);
            padding: 0.6vh 2vw;
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
            grid-template-columns: 1.4fr 1.2fr 1fr;
            align-items: center;
            padding: 0.6vh 2vw;
            border-bottom: 1px solid var(--color-border);
        }

        .column-label {
            color: var(--color-text-muted);
            font-size: var(--font-size-header);
            font-weight: 700;
            letter-spacing: 0.05em;
        }

        .column-label-status {
            text-align: right;
            padding-right: 1vw;
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
            grid-template-columns: 1.4fr 1.2fr 1fr;
            align-items: center;
            padding: 0 2vw;
        }

        .order-row-alt {
            background-color: var(--color-bg-row-alt);
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

        .cell-ticket {
            color: var(--color-text-ticket);
            font-size: var(--font-size-ticket);
            font-weight: 700;
            font-family: monospace;
        }

        .cell-status-container {
            display: flex;
            justify-content: flex-end;
            padding-right: 1vw;
        }

        .status-badge {
            font-family: var(--font-family-badge);
            color: var(--color-text-badge);
            font-size: var(--font-size-badge);
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            width: 120px;
            padding: 0.3vh 0;
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .badge-pending { background-color: var(--color-status-pending); }
        .badge-progress { background-color: var(--color-status-progress); }
        .badge-completed { background-color: var(--color-status-completed); }

        /* Panel Derecho (Dividido en INICIAL y FINAL) */
        .right-panel {
            grid-area: content-right;
            grid-area: header-right / content-right; /* Ocupa la columna derecha completa */
            display: grid;
            grid-template-rows: 1fr 1fr;
            min-height: 0;
        }

        .screen-header-right {
            grid-area: header-right;
            border-bottom: 1px solid var(--color-border);
            padding: 0.6vh 2vw;
            text-align: center;
        }

        .sub-section {
            display: flex;
            flex-direction: column;
            border-bottom: 1px solid var(--color-border);
            min-height: 0;
        }

        .sub-section:last-child {
            border-bottom: none;
        }

        .sub-section-title {
            color: var(--color-text-main);
            font-size: var(--font-size-header);
            font-weight: 800;
            text-align: center;
            padding: 0.5vh 0;
            border-bottom: 1px solid var(--color-border);
            background-color: rgba(30, 41, 59, 0.2);
            letter-spacing: 0.05em;
        }

        .empty-rows-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 0;
        }

        .empty-row {
            flex: 1;
            border-bottom: 1px dashed rgba(51, 65, 85, 0.4);
        }
        .empty-row:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <div x-data="pickerApp" class="screen-container">
        
        {{-- Encabezado Izquierdo --}}
        <header class="screen-header-left">
            <h1 class="screen-title">LISTADO</h1>
        </header>

        {{-- Encabezado Derecho --}}
        <header class="screen-header-right">
            <h1 class="screen-title">INICIAL / FINAL</h1>
        </header>

        {{-- Panel Izquierdo (LISTADO con 12 filas dinámicas) --}}
        <div class="left-panel">
            <div class="columns-header">
                <span class="column-label">PICKERO</span>
                <span class="column-label">TICKET</span>
                <div class="cell-status-container">
                    <span class="column-label column-label-status">ESTADO</span>
                </div>
            </div>

            <div class="rows-container">
                <template x-for="(item, index) in listado" :key="index">
                    <div class="order-row" :class="{ 'order-row-alt': index % 2 === 0 }">
                        <span class="cell-picker" x-text="item.picker"></span>
                        <span class="cell-ticket" x-text="item.ticket"></span>
                        <div class="cell-status-container">
                            <span
                                class="status-badge"
                                :class="getStatusConfig(item.estado).class"
                                x-text="getStatusConfig(item.estado).label"
                            ></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Panel Derecho (Secciones INICIAL y FINAL con filas vacías) --}}
        <div class="right-panel">
            {{-- Sección Inicial --}}
            <div class="sub-section">
                <div class="sub-section-title">INICIAL</div>
                <div class="empty-rows-container">
                    <template x-for="i in 4">
                        <div class="empty-row" :class="{ 'order-row-alt': i % 2 === 0 }"></div>
                    </template>
                </div>
            </div>

            {{-- Sección Final --}}
            <div class="sub-section">
                <div class="sub-section-title">FINAL</div>
                <div class="empty-rows-container">
                    <template x-for="i in 4">
                        <div class="empty-row" :class="{ 'order-row-alt': i % 2 === 0 }"></div>
                    </template>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('pickerApp', () => ({
                listado: [],
                statusMap: {
                    'pending': { label: 'PEND.', class: 'badge-pending' },
                    'pendiente': { label: 'PEND.', class: 'badge-pending' },
                    'pend': { label: 'PEND.', class: 'badge-pending' },
                    
                    'in_progress': { label: 'PROG.', class: 'badge-progress' },
                    'preparando': { label: 'PROG.', class: 'badge-progress' },
                    'prog': { label: 'PROG.', class: 'badge-progress' },
                    
                    'completed': { label: 'COMP.', class: 'badge-completed' },
                    'completado': { label: 'COMP.', class: 'badge-completed' },
                    'comp': { label: 'COMP.', class: 'badge-completed' }
                },
                getStatusConfig(status) {
                    const key = String(status || '').toLowerCase().trim();
                    return this.statusMap[key] || { 
                        label: (status || 'PEND.').toUpperCase(), 
                        class: 'badge-pending' 
                    };
                },
                async fetchListado() {
                    try {
                        const res = await fetch('/api/picker/latest', {
                            headers: { 'Accept': 'application/json' },
                        });
                        
                        if (!res.ok) throw new Error(`HTTP Error: ${res.status}`);
                        
                        const json = await res.json();
                        // Leemos desde 'tickets' (que es lo que devuelve tu endpoint actual)
                        const lista = json.tickets ?? json.listado ?? json.data ?? (Array.isArray(json) ? json : []);

                        if (Array.isArray(lista)) {
                            this.listado = lista.map(item => ({
                                picker: item.picker || 'SIN ASIGNAR',
                                ticket: item.ticket_number || item.id,
                                estado: item.status || 'pending'
                            }));
                        }
                    } catch (e) {
                        console.error('Error al actualizar listado de pickers:', e);
                    }
                },
                init() {
                    this.fetchListado();
                    setInterval(() => this.fetchListado(), 30000);
                }
            }));
        });
    </script>
</body>
</html>