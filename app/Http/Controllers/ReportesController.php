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
        // 1. Fecha seleccionada (o hoy por defecto)
        $fecha = $request->input('fecha', date('Y-m-d'));

        // 2. Total de tickets creados en la fecha
        $totalTickets = Ticket::whereDate('created_at', $fecha)->count();

        // 3. Tareas pendientes creadas en la fecha
        $pendientes = PickingTask::whereDate('created_at', $fecha)
            ->where('status', '!=', 'COMPLETADO')
            ->count();

        // 4. Tareas completadas en la fecha (según su fecha de actualización/cierre)
        $completados = PickingTask::whereDate('updated_at', $fecha)
            ->where('status', 'COMPLETADO')
            ->count();

        // 5. Tiempo promedio de ciclo para las tareas completadas hoy
        $tareasCompletadasHoy = PickingTask::where('status', 'COMPLETADO')
            ->whereDate('updated_at', $fecha)
            ->get();

        $minutosTotales = 0;
        $conteoTiempo = 0;

        foreach ($tareasCompletadasHoy as $tarea) {
            if ($tarea->created_at && $tarea->updated_at) {
                $minutosTotales += Carbon::parse($tarea->created_at)->diffInMinutes(Carbon::parse($tarea->updated_at));
                $conteoTiempo++;
            }
        }

        $tiempoPromedioCiclo = $conteoTiempo > 0 ? round($minutosTotales / $conteoTiempo, 1) : 0;

        // 6. Rendimiento por Picker con consultas limpias y separadas
        $pickers = Picker::all();
        $rendimientoPickers = [];

        foreach ($pickers as $picker) {
            // Buscar los IDs de tareas asignadas a este picker
            $taskIds = DB::table('picking_assignments')
                ->where('picker_id', $picker->id)
                ->pluck('picking_task_id');

            // Tareas completadas por este picker en la fecha seleccionada
            $completadasPicker = PickingTask::whereIn('id', $taskIds)
                ->where('status', 'COMPLETADO')
                ->whereDate('updated_at', $fecha)
                ->get();

            // Calcular tiempo promedio individual
            $tMinutos = 0;
            $tCount = 0;
            foreach ($completadasPicker as $task) {
                if ($task->created_at && $task->updated_at) {
                    $tMinutos += Carbon::parse($task->created_at)->diffInMinutes(Carbon::parse($task->updated_at));
                    $tCount++;
                }
            }
            $promedioPicker = $tCount > 0 ? round($tMinutos / $tCount, 1) : 0;

            // Carga actual (tareas asignadas que NO están completadas)
            $cargaActual = PickingTask::whereIn('id', $taskIds)
                ->where('status', '!=', 'COMPLETADO')
                ->count();

            // Definir estado visual
            if ($cargaActual > 0) {
                $badgeEstado = "EN PICKING ({$cargaActual})";
                $badgeClase = "bg-rose-500/15 text-rose-400 border-rose-500/25";
            } else {
                $badgeEstado = "LIBRE (0)";
                $badgeClase = "bg-emerald-500/15 text-emerald-400 border-emerald-500/25";
            }

            $rendimientoPickers[] = [
                'display_name' => $picker->display_name ?? ($picker->first_name . ' ' . $picker->last_name),
                'employee_code' => $picker->employee_code,
                'zone_assigned' => $picker->zone_assigned ?? 'General',
                'completados' => $completadasPicker->count(),
                'tiempo_promedio' => $promedioPicker,
                'badge_estado' => $badgeEstado,
                'badge_clase' => $badgeClase,
            ];
        }

        return view('reportes.index', compact(
            'fecha',
            'totalTickets',
            'pendientes',
            'completados',
            'tiempoPromedioCiclo',
            'rendimientoPickers'
        ));
    }
}
