<?php

use App\Http\Controllers\Auth\Forgot;
use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\Auth\Register;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Middleware\AdminCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [Login::class, 'login']);

Route::post('/forgot-password', [Forgot::class, 'sendResetLink']);
Route::post('/reset-password', [Forgot::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/register', [Register::class, 'register'])->middleware([AdminCheck::class]);
    Route::get('/profile',[Login::class, 'profile']);
    Route::post('/logout', [Login::class, 'logout']);

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories', [CategoryController::class, 'store'])->middleware([AdminCheck::class]);
    Route::put('/categories/{id}',[CategoryController::class, 'update'])->middleware([AdminCheck::class]);
    Route::delete('/categories/{id}',[CategoryController::class, 'destroy']);

    Route::get('/expenses', [ExpenseController::class, 'index']);
    Route::post('/expenses', [ExpenseController::class, 'store']);
    Route::put('/expenses/{id}', [ExpenseController::class, 'update']);
    Route::delete('/expenses/{id}', [ExpenseController::class, 'destroy']);
});
