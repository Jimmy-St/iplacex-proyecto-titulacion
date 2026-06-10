<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    // GET /api/items (Listar todo)
    public function index()
    {
        return response()->json([
            'mensaje' => 'Mostrando todos los ítems de la base de datos'
        ]);
    }

    // POST /api/items (Crear uno nuevo)
    public function store(Request $request)
    {
        // Aquí Laravel recibe lo que mandes desde Vue usando $request->all()
        return response()->json([
            'mensaje' => '¡Ítem creado con éxito en el backend!',
            'datos_recibidos' => $request->all()
        ], 201); // Código 201 "Creado"
    }

    // GET /api/items/{id} (Mostrar uno solo detallado)
    public function show($id)
    {
        return response()->json([
            'mensaje' => "Mostrando el detalle del ítem con ID: $id"
        ]);
    }

    // PUT /api/items/{id} (Actualizar un registro)
    public function update(Request $request, $id)
    {
        return response()->jsonUnicode([
            'mensaje' => "¡Ítem con ID $id actualizado correctamente!",
            'nuevos_datos' => $request->all()
        ]);
    }

    // DELETE /api/items/{id} (Borrar un registro)
    public function destroy($id)
    {
        return response()->json([
            'mensaje' => "El ítem con ID $id ha sido eliminado"
        ]);
    }
}
