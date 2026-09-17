<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// API Controller demo
use App\Http\Controllers\ItemController;
use App\Http\Controllers\Api\TicketController;

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

// API Router Ticket
//Route::post('/tickets', [TicketController::class, 'store']);
//Route::put('/tickets/{ticket_number}', [TicketController::class, 'update']);
//Route::get('/tickets/{ticket_number}', [TicketController::class, 'show']);

// API — extensión Chrome (sin middleware auth, con sanctum o token si se requiere después)
Route::post('/ticket',                [TicketController::class, 'store']);
Route::put('/ticket/{ticket_number}', [TicketController::class, 'update']);

// TICKETS NUEVOS
Route::get('/ticket/latest', [TicketController::class, 'latest']);
Route::get('/picker/latest', [TicketController::class, 'pickerActivityLatest']);


// PASO: URL para revision de puntaje
Route::get('/pickers/scores', [App\Http\Controllers\Api\TicketController::class, 'pickerScore']);

//
Route::get('/pickers/active-tasks', [App\Http\Controllers\Api\TicketController::class, 'pickerTasks']);
