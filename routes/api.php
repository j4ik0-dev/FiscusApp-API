<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExpenseController;
use Illuminate\Support\Facades\Route;

//RUTAS PÚBLICAS
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//RUTAS PROTEGIDAS
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    // Aquí metemos tus rutas de gastos para que solo el dueño pueda verlas
    Route::apiResource('expenses', ExpenseController::class);
    // Ruta para obtener mis datos
    Route::get('/user', function (Illuminate\Http\Request $request) {
        return $request->user();
    });
});
