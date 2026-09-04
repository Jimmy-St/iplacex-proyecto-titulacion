<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\PickingTask;
use App\Models\Picker;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportesController extends Controller
{
    public function index(Request $request)
    {
        // 1. Capturar la fecha del request o usar la de hoy por defecto
        $fecha = $request->input('fecha', date('Y-m-d'));

        // 2. KPIs Globales usando filtros de fecha nativos robustos
        $totalTickets = Ticket::whereDate('created_at', $fecha)->count();

        $tareasDia = PickingTask::whereDate('created_at', $fecha)->get();

        // Desglose de estados operativos
        $pendientes = $tareasDia->where('status', 'PENDIENTE')->count();
        $enProceso = $tareasDia->where('status', 'PREPARANDO')->count();
        $completados = $tareasDia->where('status', 'COMPLETADO')->count();

        // 3. Tiempo Promedio de Ciclo: Solo tareas creadas y completadas el mismo día seleccionado
        $tareasCompletadasHoy = PickingTask::where('status', 'COMPLETADO')
            ->whereDate('updated_at', $fecha)
            ->get();

        $tiempoTotalMinutos = 0;
        $cantidadConTiempo = 0;

        foreach ($tareasCompletadasHoy as $tarea) {
            if ($tarea->created_at && $tarea->updated_at) {
                $fCreacion = Carbon::parse($tarea->created_at);
                $fActualizacion = Carbon::parse($tarea->updated_at);

                if ($fCreacion->toDateString() === $fecha && $fActualizacion->toDateString() === $fecha) {
                    $minutos = $fCreacion->diffInMinutes($fActualizacion);
                    $tiempoTotalMinutos += $minutos;
                    $cantidadConTiempo++;
                }
            }
        }

        $tiempoPromedioCiclo = $cantidadConTiempo > 0
            ? round($tiempoTotalMinutos / $cantidadConTiempo, 1)
            : 0;

        // 4. Desempeño por Picker
        $pickers = Picker::all();

        $rendimientoPickers = $pickers->map(function ($picker) use ($fecha) {

            $asignaciones = DB::table('picking_assignments')
                ->join('picking_tasks', 'picking_assignments.picking_task_id', '=', 'picking_tasks.id')
                ->where('picking_assignments.picker_id', $picker->id)
                ->select('picking_tasks.*')
                ->get();

            // Filtrar tareas completadas en la fecha seleccionada
            $completadasPicker = $asignaciones->filter(function ($task) use ($fecha) {
                $fechaActualizacion = $task->updated_at ? Carbon::parse($task->updated_at)->toDateString() : null;
                return $task->status === 'COMPLETADO' && $fechaActualizacion === $fecha;
            });

            // Tiempo promedio individual del picker (restringido al mismo día)
            $tMinutos = 0;
            $tCount = 0;
            foreach ($completadasPicker as $task) {
                if ($task->created_at && $task->updated_at) {
                    $fC = Carbon::parse($task->created_at);
                    $fA = Carbon::parse($task->updated_at);
                    if ($fC->toDateString() === $fecha && $fA->toDateString() === $fecha) {
                        $tMinutos += $fC->diffInMinutes($fA);
                        $tCount++;
                    }
                }
            }
            $promedioPicker = $tCount > 0 ? round($tMinutos / $tCount, 1) : 0;

            // Carga actual (tareas que NO están completadas)
            $cargaActual = $asignaciones->where('status', '!=', 'COMPLETADO')->count();

            if ($cargaActual > 0) {
                $badgeEstado = "EN PICKING ({$cargaActual})";
                $badgeClase = "bg-rose-500/15 text-rose-400 border-rose-500/25";
            } else {
                $badgeEstado = "LIBRE (0)";
                $badgeClase = "bg-emerald-500/15 text-emerald-400 border-emerald-500/25";
            }

            return [
                'display_name' => $picker->display_name ?? ($picker->first_name . ' ' . $picker->last_name),
                'employee_code' => $picker->employee_code,
                'zone_assigned' => $picker->zone_assigned ?? 'General',
                'completados' => $completadasPicker->count(),
                'tiempo_promedio' => $promedioPicker,
                'badge_estado' => $badgeEstado,
                'badge_clase' => $badgeClase,
            ];
        });

        // Formatear la fecha para mostrarla limpiamente en la vista (DD-MM-YYYY)
        $fechaFormateada = Carbon::parse($fecha)->format('d-m-Y');

        return view('reportes.index', compact(
            'fecha',
            'fechaFormateada',
            'totalTickets',
            'pendientes',
            'enProceso',
            'completados',
            'tiempoPromedioCiclo',
            'rendimientoPickers'
        ));
    }

    public function export(Request $request)
    {
        $fecha = $request->input('fecha', date('Y-m-d'));
        $fileName = "reporte-picking-{$fecha}.csv";

        // Obtener tickets de la fecha junto con su tarea de picking y pickers asociados
        $tickets = Ticket::with(['pickingTask.pickers'])
            ->whereDate('created_at', $fecha)
            ->get();

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function () use ($tickets) {
            $file = fopen('php://output', 'w');

            // Añadir BOM para correcta lectura de tildes en Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Cabeceras del Excel / CSV
            fputcsv($file, ['NRO TICKET', 'CLIENTE', 'VENDEDOR', 'MONTO', 'ESTADO', 'PICKERS ASIGNADOS', 'HORA INICIO', 'HORA TERMINO / PROCESO'], ';');

            foreach ($tickets as $ticket) {
                $task = $ticket->pickingTask;
                $estado = $task ? $task->status : 'PENDIENTE';

                // Extraer nombres de pickers asignados
                $pickersStr = 'SIN ASIGNAR';
                if ($task && $task->pickers && $task->pickers->count() > 0) {
                    $pickersStr = $task->pickers->pluck('display_name')->implode(', ');
                }

                $horaInicio = $ticket->created_at ? $ticket->created_at->format('d-m-Y H:i:s') : '—';

                if ($estado === 'COMPLETADO' && $task && $task->updated_at) {
                    $horaTermino = $task->updated_at->format('d-m-Y H:i:s');
                } else {
                    $horaTermino = 'En proceso';
                }

                fputcsv($file, [
                    $ticket->ticket_number,
                    $ticket->customer ?? 'SIN CLIENTE',
                    $ticket->seller ?? '—',
                    $ticket->total_amount,
                    $estado,
                    $pickersStr,
                    $horaInicio,
                    $horaTermino
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
