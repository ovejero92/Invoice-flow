<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Client;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FacturaPdfController;
use App\Http\Controllers\Freelancer;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', Admin\DashboardController::class)->name('dashboard');
    Route::resource('clientes', Admin\ClienteController::class)->except(['show']);
    Route::get('usuarios-cliente/nuevo', [Admin\UsuarioClienteController::class, 'create'])->name('usuarios-cliente.create');
    Route::post('usuarios-cliente', [Admin\UsuarioClienteController::class, 'store'])->name('usuarios-cliente.store');
    Route::resource('proyectos', Admin\ProyectoController::class);
    Route::post('proyectos/{proyecto}/tareas', [Admin\TareaController::class, 'store'])->name('proyectos.tareas.store');
    Route::patch('tareas/{tarea}', [Admin\TareaController::class, 'update'])->name('tareas.update');
    Route::delete('tareas/{tarea}', [Admin\TareaController::class, 'destroy'])->name('tareas.destroy');
    Route::get('facturacion/nueva', [Admin\FacturaController::class, 'create'])->name('facturas.create');
    Route::post('facturacion', [Admin\FacturaController::class, 'store'])->name('facturas.store');
    Route::get('facturacion', [Admin\FacturaController::class, 'index'])->name('facturas.index');
    Route::get('facturacion/{factura}', [Admin\FacturaController::class, 'show'])->name('facturas.show');
    Route::get('pagos', Admin\PagoController::class)->name('pagos.index');
});

Route::middleware(['auth', 'verified', 'role:freelancer'])->prefix('freelancer')->name('freelancer.')->group(function () {
    Route::get('/', Freelancer\DashboardController::class)->name('dashboard');
    Route::get('proyectos', [Freelancer\ProyectoController::class, 'index'])->name('proyectos.index');
    Route::get('proyectos/{proyecto}', [Freelancer\ProyectoController::class, 'show'])->name('proyectos.show');
    Route::post('registro-horas', [Freelancer\RegistroHoraController::class, 'store'])->name('registro-horas.store');
    Route::get('reporte-horas', Freelancer\ReporteHorasController::class)->name('reporte-horas');
});

Route::middleware(['auth', 'verified', 'role:client'])->prefix('cliente')->name('client.')->group(function () {
    Route::get('/', Client\DashboardController::class)->name('dashboard');
    Route::get('proyectos', [Client\ProyectoController::class, 'index'])->name('proyectos.index');
    Route::get('proyectos/{proyecto}', [Client\ProyectoController::class, 'show'])->name('proyectos.show');
    Route::get('facturas', [Client\FacturaController::class, 'index'])->name('facturas.index');
    Route::get('facturas/{factura}', [Client\FacturaController::class, 'show'])->name('facturas.show');
    Route::get('facturas/{factura}/pagar', [Client\PagoController::class, 'create'])->name('pagos.create');
    Route::post('facturas/{factura}/pagar', [Client\PagoController::class, 'store'])->name('pagos.store');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('documentos/facturas/{factura}/pdf', FacturaPdfController::class)->name('facturas.pdf');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
