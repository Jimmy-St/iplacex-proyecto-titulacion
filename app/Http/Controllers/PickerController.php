<?php

namespace App\Http\Controllers;

use App\Models\Picker;
use App\Http\Requests\StorePickerRequest;
use App\Http\Requests\UpdatePickerRequest;
use Illuminate\Http\Request;

class PickerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $pickers = Picker::query()
            ->withCount(['pickingTasks as active_tasks_count' => function ($query) {
                $query->whereIn('status', ['PENDIENTE', 'PREPARANDO']);
            }])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('display_name', 'like', "%{$search}%")
                        ->orWhere('employee_code', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('pickers.index', compact('pickers', 'search'));
    }

    public function show(Picker $picker)
    {
        // 1. Calculamos sus tareas activas actuales para los badges
        $picker->loadCount(['pickingTasks as active_tasks_count' => function ($query) {
            $query->whereIn('status', ['PENDIENTE', 'PREPARANDO']);
        }]);

        // 2. Historial paginado de tareas y tickets asociados, del más reciente al más antiguo
        $assignedTasks = $picker->pickingTasks()
            ->with('ticket')
            ->latest('picking_assignments.created_at')
            ->paginate(20);

        return view('pickers.show', compact('picker', 'assignedTasks'));
    }

    public function create()
    {
        return view('pickers.create', [
            'picker' => new Picker(['is_active' => true, 'status' => 'active']),
        ]);
    }

    public function store(StorePickerRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        Picker::create($data);

        return redirect()->route('pickers.index')
            ->with('success', 'Picker creado correctamente.');
    }

    public function edit(Picker $picker)
    {
        return view('pickers.edit', compact('picker'));
    }

    public function update(UpdatePickerRequest $request, Picker $picker)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $picker->update($data);

        return redirect()->route('pickers.index')
            ->with('success', 'Picker actualizado con éxito.');
    }

    public function destroy(Picker $picker)
    {
        $picker->delete();

        return redirect()->route('pickers.index')
            ->with('success', 'Picker eliminado del sistema.');
    }
}
