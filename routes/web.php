<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Exports (must be declared BEFORE resources to avoid wildcard matches)
    Route::get('/assets/export', [AssetController::class, 'export'])->name('assets.export');
    Route::get('/procurements/export', [ProcurementController::class, 'export'])->name('procurements.export');
    Route::get('/projects/export', [ProjectController::class, 'export'])->name('projects.export');

    // Core Resource Routes
    Route::resource('assets', AssetController::class);
    Route::post('/assets/{asset}/maintenance', [AssetController::class, 'logMaintenance'])->name('assets.maintenance');

    Route::resource('procurements', ProcurementController::class);
    Route::post('/procurements/{procurement}/approve', [ProcurementController::class, 'approve'])->name('procurements.approve');
    Route::post('/procurements/{procurement}/start', [ProcurementController::class, 'start'])->name('procurements.start');
    Route::post('/procurements/{procurement}/complete', [ProcurementController::class, 'complete'])->name('procurements.complete');
    Route::post('/procurements/{procurement}/cancel', [ProcurementController::class, 'cancel'])->name('procurements.cancel');

    Route::resource('projects', ProjectController::class);
    Route::post('/projects/{project}/progress', [ProjectController::class, 'updateProgress'])->name('projects.progress');

    // Profile Settings
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Administrative Scopes (Pre-fixed with admin/)
    Route::prefix('admin')->group(function () {
        Route::resource('departments', DepartmentController::class)->except(['create', 'show', 'edit']);
        Route::resource('categories', CategoryController::class)->except(['create', 'show', 'edit']);
        Route::resource('vendors', VendorController::class)->except(['create', 'show', 'edit']);
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    });
});

require __DIR__.'/auth.php';

