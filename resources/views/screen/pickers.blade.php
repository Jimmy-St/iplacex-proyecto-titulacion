<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado de Pedidos - Armadores</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            --purple: #9333ea;
            --purple-dark: #581c87;
            --line: #c084fc;
            --tint: #faf5ff;
            --ink: #0f172a;

            --pad: max(16px, 2vw);   /* margen lateral */
            --cols: 13fr 7fr;        /* columnas Picker | Ticket */

            --fs-sm: max(13px, min(2.3vh, 4vw));
            --fs-md: max(16px, min(3.5vh, 6vw));
            --fs-lg: max(20px, min(5vh, 7vw));
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            height: 100dvh;
            padding: 1.5vh;
            background: #f8fafc;
            color: var(--ink);
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
        }

        .screen {
            display: grid;
            grid-template-rows: auto minmax(0, 1fr);
            height: 100%;
            max-width: 1900px;
            margin: 0 auto;
            overflow: hidden;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 1rem;
            box-shadow: 0 15px 30px -5px rgba(147, 51, 234, .1);
        }

        .screen > header {
            display: grid;
            grid-template-columns: 1fr auto 1fr;   /* vacío | título | fecha */
            align-items: center;
            padding: 1.2vh var(--pad);
            background: var(--tint);
            border-bottom: 1px solid var(--line);
        }

        .title {
            grid-column: 2;
            color: var(--purple);
            font-size: var(--fs-md);
            font-weight: 900;
            letter-spacing: .08em;
            text-align: center;
            text-wrap: balance;
        }

        .date {
            grid-column: 3;
            justify-self: end;
            color: var(--purple-dark);
            font-size: var(--fs-md);
            font-weight: 800;
            font-variant-numeric: tabular-nums;
        }

        .body {
            display: grid;
            grid-template-columns: 19fr 11fr;
            min-height: 0;
        }

        /* Tareas */
        .tasks { display: flex; flex-direction: column; min-height: 0; }

        .row {
            display: grid;
            grid-template-columns: var(--cols);
            align-items: center;
            padding: .4rem var(--pad);
        }

        .head {
            background: #f3e8ff;
            border-bottom: 1px solid var(--line);
            color: var(--purple-dark);
            font-size: var(--fs-sm);
            font-weight: 800;
            letter-spacing: .05em;
        }

        .rows {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: .6vh;
            padding: 1vh 0;
            min-height: 0;
            overflow: hidden;
        }
        .rows .row { flex: 1; }
        .rows > div:nth-of-type(odd) { background: rgba(233, 213, 255, .35); }

        .name {
            padding-right: 1vw;
            overflow: hidden;
            font-size: var(--fs-md);
            font-weight: 800;
            text-transform: uppercase;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .ticket {
            color: #334155;
            font: 800 var(--fs-lg) monospace;
        }

        /* Rankings */
        .rankings {
            display: grid;
            grid-template-rows: 1fr 1fr;
            min-height: 0;
            background: var(--tint);
            border-left: 1px solid var(--line);
        }

        .ranking {
            display: flex;
            flex-direction: column;
            min-height: 0;
            padding: 1.5vh var(--pad);
        }
        .ranking + .ranking { border-top: 1px solid var(--line); }

        h2 {
            margin-bottom: 1vh;
            padding-bottom: .5vh;
            border-bottom: 2px dashed var(--line);
            color: var(--purple);
            font-size: var(--fs-sm);
            font-weight: 900;
            letter-spacing: .05em;
            text-align: center;
        }

        ol {
            flex: 1;
            display: flex;
            flex-direction: column;
            list-style: none;
        }

        li {
            flex: 1;                       /* reparte la altura entre las filas que lleguen */
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: .3rem 0;
            font-size: var(--fs-md);
            font-weight: 800;
            text-transform: uppercase;
        }
        li b { color: var(--purple); font: 900 1.6em monospace; }
        li + li { border-top: 1px solid rgba(192, 132, 252, .35); }

        @media (max-width: 800px), (orientation: portrait) {
            :root { --cols: 3fr 2fr; }
            body { height: auto; min-height: 100dvh; }
            .screen { height: auto; }
            .screen > header { grid-template-columns: 1fr; gap: .3rem; }
            .title, .date { grid-column: 1; justify-self: center; }
            .body { grid-template-columns: 1fr; }
            .rankings { grid-template-rows: auto; border-left: 0; border-top: 1px solid var(--line); }
        }
    </style>
</head>
<body>
    <main x-data="armadoresApp" class="screen">
        <header>
            <h1 class="title">ASIGNACIÓN DE PEDIDOS</h1>
            <time class="date" datetime="{{ now()->toDateString() }}">{{ now()->format('d/m/Y') }}</time>
        </header>
        <div class="body">
            <section class="tasks">
                <div class="row head">
                    <span>PICKER</span>
                    <span>TICKET ASIGNADO</span>
                </div>
                <div class="rows">
                    <template x-for="task in tasks" :key="task.ticket_number + '-' + task.picker_name">
                        <div class="row">
                            <span class="name" x-text="task.picker_name"></span>
                            <span class="ticket" x-text="task.ticket_number"></span>
                        </div>
                    </template>
                </div>
            </section>

            <aside class="rankings">
                <section class="ranking">
                    <h2>PUNTEROS</h2>
                    <ol>
                        <template x-for="item in top" :key="item.picker_id">
                            <li>
                                <span x-text="item.display_name"></span>
                                <b x-text="item.final_score"></b>
                            </li>
                        </template>
                    </ol>
                </section>

                <section class="ranking">
                    <h2>COLISTAS</h2>
                    <ol>
                        <template x-for="item in bottom" :key="item.picker_id">
                            <li>
                                <span x-text="item.display_name"></span>
                                <b x-text="item.final_score"></b>
                            </li>
                        </template>
                    </ol>
                </section>
            </aside>
        </div>
    </main>

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
                        console.error('Error tareas activas:', e);
                    }
                },
                async fetchScores() {
                    try {
                        const res = await fetch('/api/pickers/scores2', {
                            headers: { 'Accept': 'application/json' },
                        });
                        if (!res.ok) throw new Error(`HTTP Error: ${res.status}`);
                        const json = await res.json();
                        this.top = json.top ?? [];
                        this.bottom = json.bottom ?? [];
                    } catch (e) {
                        console.error('Error puntajes:', e);
                    }
                },
                init() {
                    this.fetchActiveTasks();
                    this.fetchScores();
                    setInterval(() => {
                        this.fetchActiveTasks();
                        this.tickCount++;
                        if (this.tickCount >= 80) {
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