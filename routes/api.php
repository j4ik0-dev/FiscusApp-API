<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExpenseController;
use Illuminate\Support\Facades\Route;

//RUTAS PÚBLICAS
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//RUTAS PROTEGIDAS
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/user/update', [UserController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('expenses', ExpenseController::class);
    Route::get('/user', function (Illuminate\Http\Request $request) {
        return $request->user();
    });
});
