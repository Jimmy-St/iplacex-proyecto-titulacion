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
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('display_name', 'like', "%{$search}%")
                        ->orWhere('employee_code', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('pickers.index', compact('pickers', 'search'));
    }

    public function show(Picker $picker)
    {
        return view('pickers.show', compact('picker'));
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
        $picker->delete(); // Soft delete automático

        return redirect()->route('pickers.index')
            ->with('success', 'Picker eliminado del sistema.');
    }
}
