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

// Root → login
Route::get('/', fn () => redirect()->route('login'));

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Authenticated routes
Route::middleware('auth')->group(function () {

    // Dashboard — DashboardController redirects super_admin to superadmin.dashboard,
    // otherwise renders dashboard.admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Super Admin only ──
    Route::middleware('role:super_admin')->group(function () {
        Route::get('/superadmin/dashboard', [SuperAdminDashboardController::class, 'index'])->name('superadmin.dashboard');
        Route::resource('users', UserController::class)->names('admin.users');
    });

    // ── Admin + Super Admin ──
    Route::middleware('role:admin|super_admin')->group(function () {

        // Deceased persons
        Route::resource('deceased', DeceasedPersonController::class);

        // Burial permits
        Route::resource('permits', BurialPermitController::class);
        Route::post('permits/{permit}/approve', [BurialPermitController::class, 'approve'])->name('permits.approve');
        Route::post('permits/{permit}/release', [BurialPermitController::class, 'release'])->name('permits.release');

        // Cemetery map
        Route::get('/cemetery/map', [CemeteryMapController::class, 'index'])->name('cemetery.map');

        // Documents & SMS
        Route::post('permits/{permit}/documents', [DocumentController::class, 'upload'])->name('documents.upload');
        Route::post('permits/{permit}/sms', [SmsController::class, 'send'])->name('sms.send');

        // Import
        Route::post('/import/excel', [ImportController::class, 'importExcel'])->name('import.excel');

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
    });
});