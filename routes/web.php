<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PickerController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\ConfigController;

use App\Http\Controllers\DemoController;

// Rutas de invitados (Si ya estás logueado, te saca del login y te manda a los tickets)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Ruta de cierre de sesión (Solo accesible si estás autenticado)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Pantallas públicas / informativas (según definiste en tu estructura)
Route::view('/screen/customers', 'screen.customers')->name('screen.customers');
Route::view('/screen/pickers',   'screen.pickers')->name('screen.pickers');

// Secciones protegidas
Route::middleware('auth')->group(function () {

    Route::get('/', fn() => redirect()->route('tickets.index'));

    // Tickets — vistas web
    Route::get('/tickets',         [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/ticket/{numero}', [TicketController::class, 'show'])->name('tickets.show');

    //Actualiza Pickers por Ticket
    //TODO: Habilitar una vez probado 
    //Route::post('/ticket/update-pickers', [TicketController::class, 'updatePickers'])->name('ticket.update-pickers');

    // Pickers — CRUD completo
    Route::resource('pickers', PickerController::class);

    // Resto de secciones
    Route::get('/productos', [ProductosController::class, 'index'])->name('productos.index');
    Route::get('/reportes',  [ReportesController::class,  'index'])->name('reportes.index');
    Route::get('/config',    [ConfigController::class,    'index'])->name('config.index');
});

// TODO: Quitar de acá, pasar a AUTENTICACION
Route::post('/ticket/update-pickers', [TicketController::class, 'updatePickers'])->name('ticket.update-pickers');
Route::post('/ticket/complete-ticket', [TicketController::class, 'completeTicket'])->name('ticket.complete');
