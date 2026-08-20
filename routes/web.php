<?php

// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\AuthController;
// use App\Http\Controllers\TicketController;
// use App\Http\Controllers\PickerController;

// // Ruta raíz: Login
// Route::get('/', [AuthController::class, 'showLogin'])->name('login');
// Route::post('/login', [AuthController::class, 'login']);
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// // Rutas protegidas: Solo acceden si están autenticados
// Route::middleware(['auth'])->group(function () {

//     // Tickets
//     Route::get('/tickets', [TicketController::class, 'index']);
//     Route::get('/ticket/{numero}', [TicketController::class, 'show']);

//     // Pickers
//     Route::get('/pickers', [PickerController::class, 'index']);
//     Route::get('/picker/{id}', [PickerController::class, 'show']);
// });

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PickersController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\ConfigController;

// Login
Route::get('/login',   [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',  [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Pantallas
Route::view('/screen/customers', 'screen.customers')->name('screen.customers');
//Route::view('/screen/pickers',   'screen.pickers')->name('screen.pickers');
//Route::view('/screen/customers', 'screen.splash_customers')->name('screen.customers');
Route::view('/screen/pickers',   'screen.splash_pickers')->name('screen.pickers');


// Secciones protegidas
Route::middleware('auth')->group(function () {

    Route::get('/', fn() => redirect()->route('tickets.index'));

    // Tickets — vistas web
    Route::get('/tickets',         [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/ticket/{numero}', [TicketController::class, 'show'])->name('tickets.show');

    // Resto de secciones
    Route::get('/pickers',   [PickersController::class,   'index'])->name('pickers.index');
    Route::get('/productos', [ProductosController::class, 'index'])->name('productos.index');
    Route::get('/reportes',  [ReportesController::class,  'index'])->name('reportes.index');
    Route::get('/config',    [ConfigController::class,    'index'])->name('config.index');
});
