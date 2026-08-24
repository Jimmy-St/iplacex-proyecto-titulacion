<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PickingTask;

class PickingAssignmentController extends Controller
{
    public function assign(Request $request, $taskId)
    {
        // 1. Buscamos la tarea o falla con 404 si no existe
        $task = PickingTask::findOrFail($taskId);

        // 2. Validamos que venga el ID del picker
        $request->validate([
            'picker_id' => 'required|exists:pickers,id'
        ]);

        // 3. Asignamos el picker usando la relación N:N
        $task->pickers()->syncWithoutDetaching([$request->picker_id]);

        return response()->json([
            'message' => 'Picker asignado exitosamente a la tarea',
        ]);
    }
}
