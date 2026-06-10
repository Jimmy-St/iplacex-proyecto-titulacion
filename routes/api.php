<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// API Controller demo
use App\Http\Controllers\ItemController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// PRUEBA API
Route::get('/prueba-items', function () {
    return response()->json([
        'status' => 'success',
        'mensaje' => 'Conexión exitosa con Laravel 13',
        'proyecto' => 'API de Gestión',
        'items_simulados' => [
            ['id' => 1, 'nombre' => 'Item Alfa', 'estado' => 'Activo'],
            ['id' => 2, 'nombre' => 'Item Beta', 'estado' => 'Pendiente'],
            ['id' => 3, 'nombre' => 'Item Gamma', 'estado' => 'Inactivo']
        ]
    ]);
});

// API Router demo
Route::apiResource('items', ItemController::class);
