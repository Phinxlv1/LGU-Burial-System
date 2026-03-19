<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuperAdminDashboardController;
use App\Http\Controllers\DeceasedPersonController;
use App\Http\Controllers\BurialPermitController;
use App\Http\Controllers\CemeteryMapController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login'));

Route::get('/login',  [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Super Admin only ──
    Route::middleware(['role:super_admin'])->group(function () {
        Route::get('/superadmin/dashboard', [SuperAdminDashboardController::class, 'index'])->name('superadmin.dashboard');
        Route::resource('users', UserController::class)->names('admin.users');
    });

    // ── Admin + Super Admin ──
    Route::middleware(['role:admin|super_admin'])->group(function () {

        Route::resource('deceased', DeceasedPersonController::class);

        Route::resource('permits', BurialPermitController::class);
        Route::post('permits/{permit}/approve', [BurialPermitController::class, 'approve'])->name('permits.approve');
        Route::post('permits/{permit}/release', [BurialPermitController::class, 'release'])->name('permits.release');
        Route::get('permits/{permit}/print',    [BurialPermitController::class, 'print'])->name('permits.print');

        Route::get('/cemetery/map', [CemeteryMapController::class, 'index'])->name('cemetery.map');

        Route::post('permits/{permit}/documents', [DocumentController::class, 'upload'])->name('documents.upload');
        Route::post('permits/{permit}/sms',       [SmsController::class, 'send'])->name('sms.send');

        Route::get('/import/excel',  [ImportController::class, 'showImport'])->name('import.show');
        Route::post('/import/excel', [ImportController::class, 'importExcel'])->name('import.excel');

        Route::get('/reports',        [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
    });

});