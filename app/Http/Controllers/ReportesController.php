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
        $fecha = $request->input('fecha', date('Y-m-d'));

        // KPIs Globales
        $totalTickets = Ticket::whereDate('created_at', $fecha)->count();
        $tareasDia = PickingTask::whereDate('created_at', $fecha)->get();

        $pendientes = $tareasDia->where('status', 'PENDIENTE')->count();
        $enProceso = $tareasDia->where('status', 'PREPARANDO')->count();
        $completados = $tareasDia->where('status', 'COMPLETADO')->count();

        // Tiempo Promedio de Ciclo
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

        // Desempeño por Picker con Nombre + Apellido Completo
        $pickers = Picker::all();

        $rendimientoPickers = $pickers->map(function ($picker) use ($fecha) {
            $asignaciones = DB::table('picking_assignments')
                ->join('picking_tasks', 'picking_assignments.picking_task_id', '=', 'picking_tasks.id')
                ->where('picking_assignments.picker_id', $picker->id)
                ->select('picking_tasks.*')
                ->get();

            $completadasPicker = $asignaciones->filter(function ($task) use ($fecha) {
                $fechaActualizacion = $task->updated_at ? Carbon::parse($task->updated_at)->toDateString() : null;
                return $task->status === 'COMPLETADO' && $fechaActualizacion === $fecha;
            });

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
            $cargaActual = $asignaciones->where('status', '!=', 'COMPLETADO')->count();

            if ($cargaActual > 0) {
                $badgeEstado = "EN PICKING ({$cargaActual})";
                $badgeClase = "bg-rose-500/15 text-rose-400 border-rose-500/25";
            } else {
                $badgeEstado = "LIBRE (0)";
                $badgeClase = "bg-emerald-500/15 text-emerald-400 border-emerald-500/25";
            }

            // Nombre y Apellido completo formal
            $nombreCompleto = trim(($picker->first_name ?? '') . ' ' . ($picker->last_name ?? ''));
            if (empty($nombreCompleto)) {
                $nombreCompleto = $picker->display_name ?? 'SIN NOMBRE';
            }

            return [
                'display_name' => $nombreCompleto,
                'employee_code' => $picker->employee_code,
                'zone_assigned' => $picker->zone_assigned ?? 'General',
                'completados' => $completadasPicker->count(),
                'tiempo_promedio' => $promedioPicker,
                'badge_estado' => $badgeEstado,
                'badge_clase' => $badgeClase,
            ];
        });

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

            // UTF-8 BOM para soporte correcto en Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Cabeceras exactas solicitadas
            fputcsv($file, [
                'TICKET',
                'CLIENTE',
                'VENDEDOR',
                'MONTO',
                'ESTADO',
                'PICKERS',
                'INICIO',
                'FIN',
                'TIEMPO (min)'
            ], ';');

            foreach ($tickets as $ticket) {
                $task = $ticket->pickingTask;
                $estado = $task ? $task->status : 'PENDIENTE';

                // Nombres completos de los pickers
                $pickersStr = 'SIN ASIGNAR';
                if ($task && $task->pickers && $task->pickers->count() > 0) {
                    $nombres = $task->pickers->map(function ($p) {
                        $full = trim(($p->first_name ?? '') . ' ' . ($p->last_name ?? ''));
                        return !empty($full) ? $full : ($p->display_name ?? 'Picker');
                    });
                    $pickersStr = $nombres->implode(', ');
                }

                $horaInicio = $ticket->created_at ? $ticket->created_at->format('d-m-Y H:i') : '—';

                if ($estado === 'COMPLETADO' && $task && $task->updated_at) {
                    $horaFin = $task->updated_at->format('d-m-Y H:i');
                    // Tiempo redondeado sin letras, solo número entero
                    $minutosUsados = $ticket->created_at ? round($ticket->created_at->diffInMinutes($task->updated_at)) : '';
                } else {
                    $horaFin = 'En proceso';
                    $minutosUsados = '';
                }

                // Monto con símbolo $
                $montoFormateado = '$' . number_format($ticket->total_amount, 0, ',', '');

                fputcsv($file, [
                    $ticket->ticket_number,
                    $ticket->customer ?? 'SIN CLIENTE',
                    $ticket->seller ?? '—',
                    $montoFormateado,
                    $estado,
                    $pickersStr,
                    $horaInicio,
                    $horaFin,
                    $minutosUsados
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
