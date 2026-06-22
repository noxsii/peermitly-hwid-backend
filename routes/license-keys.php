<?php

declare(strict_types=1);

use App\Http\Controllers\LicenseKeys\CustomerController;
use App\Http\Controllers\LicenseKeys\LicenseKeyController;
use App\Http\Controllers\LicenseKeys\LicenseKeyExportController;
use App\Http\Controllers\LicenseKeys\LicenseKeyExtendController;
use App\Http\Controllers\LicenseKeys\LicenseKeyRestoreController;
use App\Http\Controllers\LicenseKeys\LicenseKeyRevokeController;
use App\Http\Controllers\LicenseKeys\LicenseKeySearchController;
use App\Http\Controllers\LicenseKeys\LicenseKeyTypeController;
use App\Http\Controllers\LicenseKeys\LicenseKeyTypePreviewController;
use App\Http\Controllers\LicenseKeys\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('/', [LicenseKeyController::class, 'index'])->name('index');
    Route::post('/', [LicenseKeyController::class, 'store'])->name('store');

    Route::post('/bulk', [LicenseKeyController::class, 'bulkStore'])->name('bulk.store');
    Route::post('/bulk-extend', [LicenseKeyController::class, 'bulkExtend'])->name('bulk.extend');

    Route::get('/export', [LicenseKeyExportController::class, 'export'])->name('export');
    Route::get('/search', [LicenseKeySearchController::class, 'search'])->name('search');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::patch('/products/{product:uuid}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product:uuid}', [ProductController::class, 'destroy'])->name('products.destroy');

    Route::get('/types', [LicenseKeyTypeController::class, 'index'])->name('types.index');
    Route::post('/types', [LicenseKeyTypeController::class, 'store'])->name('types.store');
    Route::post('/types/preview', [LicenseKeyTypePreviewController::class, 'preview'])->name('types.preview');
    Route::patch('/types/{licenseKeyType:uuid}', [LicenseKeyTypeController::class, 'update'])->name('types.update');
    Route::delete('/types/{licenseKeyType:uuid}', [LicenseKeyTypeController::class, 'destroy'])->name('types.destroy');

    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::patch('/customers/{customer:uuid}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer:uuid}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    Route::get('/{licenseKey:uuid}', [LicenseKeyController::class, 'show'])->name('show');
    Route::patch('/{licenseKey:uuid}', [LicenseKeyController::class, 'update'])->name('update');
    Route::delete('/{licenseKey:uuid}', [LicenseKeyController::class, 'destroy'])->name('destroy');

    Route::post('/{licenseKey:uuid}/revoke', [LicenseKeyRevokeController::class, 'revoke'])->name('revoke');
    Route::post('/{licenseKey:uuid}/restore', [LicenseKeyRestoreController::class, 'restore'])->name('restore');
    Route::post('/{licenseKey:uuid}/extend', [LicenseKeyExtendController::class, 'extend'])->name('extend');
});
