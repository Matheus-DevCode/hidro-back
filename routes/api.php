<?php


use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AguaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/calcular-agua', [AguaController::class, 'calcularAgua'])->name('calcular-agua');
Route::post('/validate-token', [AuthController::class, 'validateToken']);


Route::post('/login', [AuthController::class, 'login'])->name('login');


