<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RutaController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\AdminController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/home', [HomeController::class, 'index'])->name('home.index');
Route::get('/rutas', [RutaController::class, 'index'])->name('rutas.index');
Route::get('/comentarios', [ComentarioController::class, 'index'])->name('comentarios.index');
Route::post('/comentarios', [ComentarioController::class, 'store'])->middleware('auth')->name('comentarios.store');
Route::get('/destacados', [ComentarioController::class, 'destacados'])->name('comentarios.destacados');
Route::post('/guardar-ruta', [RutaController::class, 'guardar'])->middleware('auth');
Route::get('/rutas-usuario', function () {
    return response()->json([
        'rutas' => \App\Models\Ruta::where('user_id', Auth::id())->get()
    ]);
})->middleware('auth');

Route::delete('/eliminar-ruta/{id}', function ($id) {
    $ruta = \App\Models\Ruta::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
    $ruta->delete();
    return response()->json(['success' => true]);
})->middleware('auth');


// Esto agrega las rutas de login, logout, register, password reset, etc.
Auth::routes();
use App\Http\Controllers\EmpresaController;

Route::get('/empresa/{id}', [EmpresaController::class, 'show'])->name('empresa.show');



Route::middleware(['auth', 'admin'])->group(function () {
    // Ruta para mostrar el panel de administración
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

    // Ruta para eliminar un usuario
    Route::delete('/usuarios/{id}', [AdminController::class, 'destroyUser'])->name('usuarios.destroy');

    // Ruta para eliminar un comentario
    Route::delete('/comentarios/{id}', [AdminController::class, 'destroyComment'])->name('comentarios.destroy');
});
