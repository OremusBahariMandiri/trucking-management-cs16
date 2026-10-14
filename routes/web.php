<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MasterData\Vehicle\BodyTypeController;
use App\Http\Controllers\MasterData\Vehicle\FuelTypeController;
use App\Http\Controllers\MasterData\Vehicle\VehicleController;
use App\Http\Controllers\MasterData\Vehicle\VehicleBrandController;
use App\Http\Controllers\MasterData\Vehicle\VehicleModelController;
use App\Http\Controllers\MasterData\Vehicle\VehicleTypeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ============================================================
// AUTH
// ============================================================

Auth::routes(['register' => false]);

Route::get('/', fn() => redirect()->route('login'));

// ============================================================
// AUTHENTICATED
// ============================================================

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // ──────────────────────────────────────────────────────────
    // MASTER DATA
    // ──────────────────────────────────────────────────────────
    Route::prefix('master-data')->name('master-data.')->group(function () {

        // ── Kendaraan ──────────────────────────────────────────
        Route::prefix('kendaraan')->name('kendaraan.')->group(function () {
            Route::resource('/', VehicleController::class)
                ->parameters(['' => 'vehicle'])
                ->names([
                    'index'   => 'index',
                    'create'  => 'create',
                    'store'   => 'store',
                    'show'    => 'show',
                    'edit'    => 'edit',
                    'update'  => 'update',
                    'destroy' => 'destroy',
                ]);

            // Manajemen foto
            Route::prefix('{vehicle}/foto')->name('foto.')->group(function () {
                Route::delete('{photo}',             [VehicleController::class, 'destroyPhoto'])   ->name('destroy');
                Route::patch('{photo}/set-primary',  [VehicleController::class, 'setPrimaryPhoto'])->name('set-primary');
            });

            // API / AJAX helpers
            Route::prefix('api')->name('api.')->group(function () {
                Route::get('search',    [VehicleController::class, 'apiSearch'])->name('search');
                Route::get('{vehicle}', [VehicleController::class, 'apiShow'])  ->name('show');
            });
        });

        // ── Jenis Kendaraan ────────────────────────────────────
        Route::prefix('vehicle-types')->name('vehicle-types.')->group(function () {
            Route::get('/',                        [VehicleTypeController::class, 'index'])       ->name('index');
            Route::get('/create',                  [VehicleTypeController::class, 'create'])      ->name('create');
            Route::post('/',                       [VehicleTypeController::class, 'store'])       ->name('store');
            Route::get('/{vehicle_type}/edit',     [VehicleTypeController::class, 'edit'])        ->name('edit');
            Route::put('/{vehicle_type}',          [VehicleTypeController::class, 'update'])      ->name('update');
            Route::delete('/{vehicle_type}',       [VehicleTypeController::class, 'destroy'])     ->name('destroy');
            Route::patch('/{vehicle_type}/toggle', [VehicleTypeController::class, 'toggleActive'])->name('toggle');
            Route::get('/api/list',                [VehicleTypeController::class, 'apiList'])     ->name('api.list');
        });

        // ── Merek Kendaraan ────────────────────────────────────
        Route::prefix('vehicle-brands')->name('vehicle-brands.')->group(function () {
            Route::get('/',                         [VehicleBrandController::class, 'index'])       ->name('index');
            Route::get('/create',                   [VehicleBrandController::class, 'create'])      ->name('create');
            Route::post('/',                        [VehicleBrandController::class, 'store'])       ->name('store');
            Route::get('/{vehicle_brand}/edit',     [VehicleBrandController::class, 'edit'])        ->name('edit');
            Route::put('/{vehicle_brand}',          [VehicleBrandController::class, 'update'])      ->name('update');
            Route::delete('/{vehicle_brand}',       [VehicleBrandController::class, 'destroy'])     ->name('destroy');
            Route::patch('/{vehicle_brand}/toggle', [VehicleBrandController::class, 'toggleActive'])->name('toggle');
            Route::get('/api/list',                 [VehicleBrandController::class, 'apiList'])     ->name('api.list');
        });

        // ── Model Kendaraan ────────────────────────────────────
        Route::prefix('vehicle-models')->name('vehicle-models.')->group(function () {
            Route::get('/',                          [VehicleModelController::class, 'index'])       ->name('index');
            Route::get('/create',                    [VehicleModelController::class, 'create'])      ->name('create');
            Route::post('/',                         [VehicleModelController::class, 'store'])       ->name('store');
            Route::get('/{vehicle_model}/edit',      [VehicleModelController::class, 'edit'])        ->name('edit');
            Route::put('/{vehicle_model}',           [VehicleModelController::class, 'update'])      ->name('update');
            Route::delete('/{vehicle_model}',        [VehicleModelController::class, 'destroy'])     ->name('destroy');
            Route::patch('/{vehicle_model}/toggle',  [VehicleModelController::class, 'toggleActive'])->name('toggle');
            Route::get('/api/list',                  [VehicleModelController::class, 'apiList'])     ->name('api.list');
        });

        // ── Jenis BBM ──────────────────────────────────────────
        Route::prefix('fuel-types')->name('fuel-types.')->group(function () {
            Route::get('/',                      [FuelTypeController::class, 'index'])       ->name('index');
            Route::get('/create',                [FuelTypeController::class, 'create'])      ->name('create');
            Route::post('/',                     [FuelTypeController::class, 'store'])       ->name('store');
            Route::get('/{fuel_type}/edit',      [FuelTypeController::class, 'edit'])        ->name('edit');
            Route::put('/{fuel_type}',           [FuelTypeController::class, 'update'])      ->name('update');
            Route::delete('/{fuel_type}',        [FuelTypeController::class, 'destroy'])     ->name('destroy');
            Route::patch('/{fuel_type}/toggle',  [FuelTypeController::class, 'toggleActive'])->name('toggle');
            Route::get('/api/list',              [FuelTypeController::class, 'apiList'])     ->name('api.list');
        });

        // ── Jenis Body ─────────────────────────────────────────
        Route::prefix('body-types')->name('body-types.')->group(function () {
            Route::get('/',                      [BodyTypeController::class, 'index'])       ->name('index');
            Route::get('/create',                [BodyTypeController::class, 'create'])      ->name('create');
            Route::post('/',                     [BodyTypeController::class, 'store'])       ->name('store');
            Route::get('/{body_type}/edit',      [BodyTypeController::class, 'edit'])        ->name('edit');
            Route::put('/{body_type}',           [BodyTypeController::class, 'update'])      ->name('update');
            Route::delete('/{body_type}',        [BodyTypeController::class, 'destroy'])     ->name('destroy');
            Route::patch('/{body_type}/toggle',  [BodyTypeController::class, 'toggleActive'])->name('toggle');
            Route::get('/api/list',              [BodyTypeController::class, 'apiList'])     ->name('api.list');
        });

    }); // end master-data

}); // end auth