<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado de Pedidos - Armadores</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            --color-bg-body: #f8fafc;
            --color-bg-card: #ffffff;
            --color-bg-row-alt: rgba(233, 213, 255, 0.35);
            --color-border: #c084fc;
            --color-text-main: #0f172a;
            --color-text-muted: #581c87;
            --color-text-ticket: #334155;
            --color-title: #9333ea;
            --font-family-base: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --font-size-title: 3.2vh;
            --font-size-header: 2.4vh;
            --font-size-picker: 3.5vh;
            --font-size-ticket: 5vh;
            --body-padding: 1.5vh;
            --container-max-width: 1900px;
            --container-radius: 1rem;
            --grid-padding-x: 2vw;
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
            box-shadow: 0 15px 30px -5px rgba(147, 51, 234, 0.1);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .screen-header {
            flex-shrink: 0;
            background-color: #faf5ff;
            border-bottom: 1px solid var(--color-border);
            padding: 1.2vh var(--grid-padding-x);
            text-align: center;
        }

        .screen-title {
            color: var(--color-title);
            font-size: var(--font-size-title);
            font-weight: 900;
            letter-spacing: 0.08em;
        }

        .screen-body {
            flex: 1;
            display: grid;
            grid-template-columns: 19fr 11fr;
            min-height: 0;
        }

        .left-section {
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--color-border);
            min-height: 0;
        }

        .columns-header {
            flex-shrink: 0;
            display: grid;
            grid-template-columns: 13fr 7fr;
            align-items: center;
            padding: 1vh var(--grid-padding-x);
            border-bottom: 1px solid var(--color-border);
            background-color: #f3e8ff;
        }

        .column-label {
            color: var(--color-text-muted);
            font-size: var(--font-size-header);
            font-weight: 800;
            letter-spacing: 0.05em;
        }

        .rows-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 0;
            gap: 0.6vh;
            padding: 1vh 0;
            overflow: hidden;
        }

        .order-row {
            flex: 1;
            display: grid;
            grid-template-columns: 13fr 7fr;
            align-items: center;
            padding: 0 var(--grid-padding-x);
            background-color: transparent;
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
            font-weight: 800;
            font-family: monospace;
        }

        .right-section {
            display: flex;
            flex-direction: column;
            background-color: #faf5ff;
            min-height: 0;
        }

        .ranking-box {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 1.5vh 2vw;
            border-bottom: 1px solid var(--color-border);
            min-height: 0;
        }

        .ranking-box:last-child {
            border-bottom: none;
        }

        .ranking-title {
            color: var(--color-title);
            font-size: 2.2vh;
            font-weight: 900;
            letter-spacing: 0.05em;
            margin-bottom: 1vh;
            text-align: center;
            border-bottom: 2px dashed var(--color-border);
            padding-bottom: 0.5vh;
        }

        .ranking-list {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-around;
        }

        .ranking-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #ffffff;
            border: 1px solid var(--color-border);
            border-radius: 0.5vh;
            padding: 0.8vh 1.2vw;
        }

        .ranking-name {
            color: var(--color-text-main);
            font-size: 2.2vh;
            font-weight: 800;
            text-transform: uppercase;
        }

        .ranking-score {
            background-color: #9333ea;
            color: #ffffff;
            font-size: 2.3vh;
            font-weight: 900;
            font-family: monospace;
            padding: 0.3vh 1vw;
            border-radius: 0.4vh;
        }
    </style>
</head>
<body>
    <div x-data="armadoresApp" class="screen-container">
        <header class="screen-header">
            <h1 class="screen-title">ASIGNACIÓN DE PEDIDOS ARMADORES</h1>
        </header>
        
        <div class="screen-body">
            <div class="left-section">
                <div class="columns-header">
                    <span class="column-label">PICKER</span>
                    <span class="column-label">TICKET ASIGNADO</span>
                </div>

                <div class="rows-container">
                    <template x-for="(task, index) in tasks" :key="task.ticket_number + '-' + task.picker_name">
                        <div class="order-row" :class="{ 'order-row-alt': index % 2 === 0 }">
                            <span class="cell-picker" x-text="task.picker_name"></span>
                            <span class="cell-ticket" x-text="task.ticket_number"></span>
                        </div>
                    </template>
                </div>
            </div>

            <div class="right-section">
                <div class="ranking-box">
                    <h2 class="ranking-title">PUNTEROS</h2>
                    <div class="ranking-list">
                        <template x-for="item in top" :key="item.picker_id">
                            <div class="ranking-item">
                                <span class="ranking-name" x-text="item.display_name"></span>
                                <span class="ranking-score" x-text="item.final_score"></span>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="ranking-box">
                    <h2 class="ranking-title">COLISTAS</h2>
                    <div class="ranking-list">
                        <template x-for="item in bottom" :key="item.picker_id">
                            <div class="ranking-item">
                                <span class="ranking-name" x-text="item.display_name"></span>
                                <span class="ranking-score" x-text="item.final_score"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('armadoresApp', () => ({
                tasks: [],
                top: [],
                bottom: [],
                tickCount: 0,
                async fetchActiveTasks() {
                    try {
                        const res = await fetch('/api/pickers/active-tasks', {
                            headers: { 'Accept': 'application/json' },
                        });
                        if (!res.ok) throw new Error(`HTTP Error: ${res.status}`);
                        const json = await res.json();
                        this.tasks = json.tasks ?? [];
                    } catch (e) {
                        console.error('Error fetching active tasks:', e);
                    }
                },
                async fetchScores() {
                    try {
                        const res = await fetch('/api/pickers/scores', {
                            headers: { 'Accept': 'application/json' },
                        });
                        if (!res.ok) throw new Error(`HTTP Error: ${res.status}`);
                        const json = await res.json();
                        this.top = json.top ?? [];
                        this.bottom = json.bottom ?? [];
                    } catch (e) {
                        console.error('Error fetching scores:', e);
                    }
                },
                init() {
                    this.fetchActiveTasks();
                    this.fetchScores();

                    setInterval(() => {
                        this.fetchActiveTasks();
                        this.tickCount++;
                        if (this.tickCount >= 20) {
                            this.tickCount = 0;
                            this.fetchScores();
                        }
                    }, 30000);
                }
            }));
        });
    </script>
</body>
</html>