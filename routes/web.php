<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RutaController;
use App\Http\Controllers\ComentarioController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/home', [HomeController::class, 'index'])->name('home.index');
Route::get('/rutas', [RutaController::class, 'index'])->name('rutas.index');
Route::get('/comentarios', [ComentarioController::class, 'index'])->name('comentarios.index');
Route::post('/comentarios', [ComentarioController::class, 'store'])->middleware('auth')->name('comentarios.store');
Route::get('/destacados', [ComentarioController::class, 'destacados'])->name('comentarios.destacados');


// Esto agrega las rutas de login, logout, register, password reset, etc.
Auth::routes();
use App\Http\Controllers\EmpresaController;

Route::get('/empresa/{id}', [EmpresaController::class, 'show'])->name('empresa.show');

