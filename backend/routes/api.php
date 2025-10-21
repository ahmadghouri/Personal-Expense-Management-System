<?php

use App\Http\Controllers\Auth\Admin;
use App\Http\Controllers\Auth\Forgot;
use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\Auth\Register;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\DonationTypeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportingController;
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
    Route::get('/profile', [Login::class, 'profile']);
    Route::post('/logout', [Login::class, 'logout']);

    Route::get('/admin/users', [Admin::class, 'index'])->middleware([AdminCheck::class]);
    Route::put('/admin/users/{id}', [Admin::class, 'updata'])->middleware([AdminCheck::class]);
    Route::delete('/admin/users/{id}', [Admin::class, 'delete'])->middleware([AdminCheck::class]);

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/expenses', [CategoryController::class, 'show'])->middleware([AdminCheck::class]);
    Route::post('/categories', [CategoryController::class, 'store'])->middleware([AdminCheck::class]);
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->middleware([AdminCheck::class]);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->middleware([AdminCheck::class]);

    Route::get('/expenses', [ExpenseController::class, 'index']);
    Route::post('/expenses', [ExpenseController::class, 'store']);
    Route::put('/expenses/{id}', [ExpenseController::class, 'update']);
    Route::delete('/expenses/{id}', [ExpenseController::class, 'destroy']);

    // Donation Types
    Route::get('/donation-types', [DonationTypeController::class, 'index']);
    Route::get('/donation-types/donations', [DonationTypeController::class, 'show'])->middleware([AdminCheck::class]);
    Route::post('/donation-types', [DonationTypeController::class, 'store'])->middleware([AdminCheck::class]);
    Route::put('/donation-types/{id}', [DonationTypeController::class, 'update'])->middleware([AdminCheck::class]);
    Route::delete('/donation-types/{id}', [DonationTypeController::class, 'destroy'])->middleware([AdminCheck::class]);

    // Donations
    Route::get('/donations', [DonationController::class, 'index']);
    Route::post('/donations', [DonationController::class, 'store']);
    Route::put('/donations/{id}', [DonationController::class, 'update']);
    Route::delete('/donations/{id}', [DonationController::class, 'destroy']);

    Route::get('/reports/summary', [ReportingController::class, 'summary']);
    Route::get('/reports/category', [ReportingController::class, 'categoryBreakdown']);
    Route::get('/reports/summary-category', [ReportingController::class, 'summarizedByCategory']);
    Route::get('/reports/top-categories', [ReportingController::class, 'topCategories']);
    Route::get('/reports/drilldown/{categoryId}', [ReportingController::class, 'drillDown']);
    Route::get('/reports/donation', [ReportingController::class, 'donationBreakdown']);
    Route::get('/reports/monthly', [ReportingController::class, 'monthlyTrends']);
    Route::get('/reports/pie', [ReportingController::class, 'expensePieChart']);
    Route::get('/reports/filter', [ReportingController::class, 'filter']);
    Route::get('/reports/export-pdf', [ReportingController::class, 'exportPdf']);
    Route::get('/reports/export-excel', [ReportingController::class, 'exportExcel']);

});
