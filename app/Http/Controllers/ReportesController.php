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

        $pendientes = $tareasDia->where('status', '!=', 'COMPLETADO')->count();
        $completados = $tareasDia->where('status', 'COMPLETADO')->count();

        // 3. Tiempo Promedio de Ciclo (en minutos) para tareas COMPLETADAS en la fecha
        $tareasCompletadas = PickingTask::where('status', 'COMPLETADO')
            ->whereDate('updated_at', $fecha)
            ->get();

        $tiempoTotalMinutos = 0;
        $cantidadConTiempo = 0;

        foreach ($tareasCompletadas as $tarea) {
            if ($tarea->created_at && $tarea->updated_at) {
                $minutos = Carbon::parse($tarea->created_at)->diffInMinutes(Carbon::parse($tarea->updated_at));
                $tiempoTotalMinutos += $minutos;
                $cantidadConTiempo++;
            }
        }

        $tiempoPromedioCiclo = $cantidadConTiempo > 0
            ? round($tiempoTotalMinutos / $cantidadConTiempo, 1)
            : 0;

        // 4. Desempeño por Picker
        $pickers = Picker::all();

        // Pasamos explícitamente $fecha dentro del use() para que la closure la reconozca sin errores
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

            // Tiempo promedio individual del picker
            $tMinutos = 0;
            $tCount = 0;
            foreach ($completadasPicker as $task) {
                if ($task->created_at && $task->updated_at) {
                    $tMinutos += Carbon::parse($task->created_at)->diffInMinutes(Carbon::parse($task->updated_at));
                    $tCount++;
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
