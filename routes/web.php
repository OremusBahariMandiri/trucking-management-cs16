<?php

use App\Http\Controllers\DataManagement\FleetManagement\FleetController;
use App\Http\Controllers\DataManagement\SdmManagement\DriverManagement\DriverController;
use App\Http\Controllers\DataManagement\SdmManagement\EmployeeManagement\EmployeeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MasterData\Administration\BusinessTypeController;
use App\Http\Controllers\MasterData\Administration\CustomerController;
use App\Http\Controllers\MasterData\Administration\CustomerTypeController;
use App\Http\Controllers\MasterData\Vehicle\BodyTypeController;
use App\Http\Controllers\MasterData\Vehicle\FuelTypeController;
use App\Http\Controllers\MasterData\Vehicle\OwnershipStatusController;
use App\Http\Controllers\MasterData\Vehicle\VehicleAxisConfigurationController;
use App\Http\Controllers\MasterData\Vehicle\VehicleBrandController;
use App\Http\Controllers\MasterData\Vehicle\VehicleModelController;
use App\Http\Controllers\MasterData\Vehicle\VehicleTypeController;
use App\Http\Controllers\UserManagement\PermissionController;
use App\Http\Controllers\UserManagement\RoleController;
use App\Http\Controllers\UserManagement\UserController;
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

// Route::middleware(['auth'])->group(function () {
Route::middleware(['auth', 'permission'])->group(function () {

    // Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // ──────────────────────────────────────────────────────────
    // MASTER DATA
    // ──────────────────────────────────────────────────────────
    Route::prefix('master-data')->name('master-data.')->group(function () {

        // ── Jenis Kendaraan ────────────────────────────────────
        Route::prefix('vehicle-types')->name('vehicle-types.')->group(function () {
            Route::get('/',                        [VehicleTypeController::class, 'index'])->name('index');
            Route::get('/create',                  [VehicleTypeController::class, 'create'])->name('create');
            Route::post('/',                       [VehicleTypeController::class, 'store'])->name('store');
            Route::get('/{vehicle_type}/edit',     [VehicleTypeController::class, 'edit'])->name('edit');
            Route::put('/{vehicle_type}',          [VehicleTypeController::class, 'update'])->name('update');
            Route::delete('/{vehicle_type}',       [VehicleTypeController::class, 'destroy'])->name('destroy');
            Route::patch('/{vehicle_type}/toggle', [VehicleTypeController::class, 'toggleActive'])->name('toggle');
            Route::get('/api/list',                [VehicleTypeController::class, 'apiList'])->name('api.list');
        });

        // ── Merek Kendaraan ────────────────────────────────────
        Route::prefix('vehicle-brands')->name('vehicle-brands.')->group(function () {
            Route::get('/',                         [VehicleBrandController::class, 'index'])->name('index');
            Route::get('/create',                   [VehicleBrandController::class, 'create'])->name('create');
            Route::post('/',                        [VehicleBrandController::class, 'store'])->name('store');
            Route::get('/{vehicle_brand}/edit',     [VehicleBrandController::class, 'edit'])->name('edit');
            Route::put('/{vehicle_brand}',          [VehicleBrandController::class, 'update'])->name('update');
            Route::delete('/{vehicle_brand}',       [VehicleBrandController::class, 'destroy'])->name('destroy');
            Route::patch('/{vehicle_brand}/toggle', [VehicleBrandController::class, 'toggleActive'])->name('toggle');
            Route::get('/api/list',                 [VehicleBrandController::class, 'apiList'])->name('api.list');
        });

        // ── Model Kendaraan ────────────────────────────────────
        Route::prefix('vehicle-models')->name('vehicle-models.')->group(function () {
            Route::get('/',                          [VehicleModelController::class, 'index'])->name('index');
            Route::get('/create',                    [VehicleModelController::class, 'create'])->name('create');
            Route::post('/',                         [VehicleModelController::class, 'store'])->name('store');
            Route::get('/{vehicle_model}/edit',      [VehicleModelController::class, 'edit'])->name('edit');
            Route::put('/{vehicle_model}',           [VehicleModelController::class, 'update'])->name('update');
            Route::delete('/{vehicle_model}',        [VehicleModelController::class, 'destroy'])->name('destroy');
            Route::patch('/{vehicle_model}/toggle',  [VehicleModelController::class, 'toggleActive'])->name('toggle');
            Route::get('/api/list',                  [VehicleModelController::class, 'apiList'])->name('api.list');
        });

        // ── Jenis BBM ──────────────────────────────────────────
        Route::prefix('fuel-types')->name('fuel-types.')->group(function () {
            Route::get('/',                      [FuelTypeController::class, 'index'])->name('index');
            Route::get('/create',                [FuelTypeController::class, 'create'])->name('create');
            Route::post('/',                     [FuelTypeController::class, 'store'])->name('store');
            Route::get('/{fuel_type}/edit',      [FuelTypeController::class, 'edit'])->name('edit');
            Route::put('/{fuel_type}',           [FuelTypeController::class, 'update'])->name('update');
            Route::delete('/{fuel_type}',        [FuelTypeController::class, 'destroy'])->name('destroy');
            Route::patch('/{fuel_type}/toggle',  [FuelTypeController::class, 'toggleActive'])->name('toggle');
            Route::get('/api/list',              [FuelTypeController::class, 'apiList'])->name('api.list');
        });

        // ── Jenis Body ─────────────────────────────────────────
        Route::prefix('body-types')->name('body-types.')->group(function () {
            Route::get('/',                      [BodyTypeController::class, 'index'])->name('index');
            Route::get('/create',                [BodyTypeController::class, 'create'])->name('create');
            Route::post('/',                     [BodyTypeController::class, 'store'])->name('store');
            Route::get('/{body_type}/edit',      [BodyTypeController::class, 'edit'])->name('edit');
            Route::put('/{body_type}',           [BodyTypeController::class, 'update'])->name('update');
            Route::delete('/{body_type}',        [BodyTypeController::class, 'destroy'])->name('destroy');
            Route::patch('/{body_type}/toggle',  [BodyTypeController::class, 'toggleActive'])->name('toggle');
            Route::get('/api/list',              [BodyTypeController::class, 'apiList'])->name('api.list');
        });

        // ── aa09: Vehicle Axis Configurations ────────────────────────────────────────
        Route::prefix('axis-configurations')->name('axis-configurations.')->group(function () {
            Route::get('/',                              [VehicleAxisConfigurationController::class, 'index'])->name('index');
            Route::get('/create',                        [VehicleAxisConfigurationController::class, 'create'])->name('create');
            Route::post('/',                             [VehicleAxisConfigurationController::class, 'store'])->name('store');
            Route::get('/{axis_configuration}/edit',     [VehicleAxisConfigurationController::class, 'edit'])->name('edit');
            Route::put('/{axis_configuration}',          [VehicleAxisConfigurationController::class, 'update'])->name('update');
            Route::delete('/{axis_configuration}',       [VehicleAxisConfigurationController::class, 'destroy'])->name('destroy');
            Route::patch('/{axis_configuration}/toggle', [VehicleAxisConfigurationController::class, 'toggle'])->name('toggle');
            Route::get('/api/list',                      [VehicleAxisConfigurationController::class, 'apiList'])->name('api.list');
        });

        // ── aa10: Ownership Statuses ──────────────────────────────────────────────────
        Route::prefix('ownership-statuses')->name('ownership-statuses.')->group(function () {
            Route::get('/',                            [OwnershipStatusController::class, 'index'])->name('index');
            Route::get('/create',                      [OwnershipStatusController::class, 'create'])->name('create');
            Route::post('/',                           [OwnershipStatusController::class, 'store'])->name('store');
            Route::get('/{ownership_status}/edit',     [OwnershipStatusController::class, 'edit'])->name('edit');
            Route::put('/{ownership_status}',          [OwnershipStatusController::class, 'update'])->name('update');
            Route::delete('/{ownership_status}',       [OwnershipStatusController::class, 'destroy'])->name('destroy');
            Route::patch('/{ownership_status}/toggle', [OwnershipStatusController::class, 'toggle'])->name('toggle');
            Route::get('/api/list',                    [OwnershipStatusController::class, 'apiList'])->name('api.list');
        });

        // ── Administrasi ───────────────────────────────────────
        Route::prefix('administration')->name('administration.')->group(function () {

            // Jenis Pelanggan
            Route::prefix('customer-types')->name('customer-types.')->group(function () {
                Route::get('/',                          [CustomerTypeController::class, 'index'])->name('index');
                Route::get('/create',                    [CustomerTypeController::class, 'create'])->name('create');
                Route::post('/',                         [CustomerTypeController::class, 'store'])->name('store');
                Route::get('/{customer_type}/edit',      [CustomerTypeController::class, 'edit'])->name('edit');
                Route::put('/{customer_type}',           [CustomerTypeController::class, 'update'])->name('update');
                Route::delete('/{customer_type}',        [CustomerTypeController::class, 'destroy'])->name('destroy');
                Route::patch('/{customer_type}/toggle',  [CustomerTypeController::class, 'toggle'])->name('toggle');
                Route::get('/api/list',                  [CustomerTypeController::class, 'apiList'])->name('api.list');
            });

            // Jenis Usaha
            Route::prefix('business-types')->name('business-types.')->group(function () {
                Route::get('/',                          [BusinessTypeController::class, 'index'])->name('index');
                Route::get('/create',                    [BusinessTypeController::class, 'create'])->name('create');
                Route::post('/',                         [BusinessTypeController::class, 'store'])->name('store');
                Route::get('/{business_type}/edit',      [BusinessTypeController::class, 'edit'])->name('edit');
                Route::put('/{business_type}',           [BusinessTypeController::class, 'update'])->name('update');
                Route::delete('/{business_type}',        [BusinessTypeController::class, 'destroy'])->name('destroy');
                Route::patch('/{business_type}/toggle',  [BusinessTypeController::class, 'toggle'])->name('toggle');
                Route::get('/api/list',                  [BusinessTypeController::class, 'apiList'])->name('api.list');
            });

            // Data Pelanggan
            Route::prefix('customers')->name('customers.')->group(function () {
                Route::get('/',                      [CustomerController::class, 'index'])->name('index');
                Route::get('/create',                [CustomerController::class, 'create'])->name('create');
                Route::post('/',                     [CustomerController::class, 'store'])->name('store');
                Route::get('/{customer}',            [CustomerController::class, 'show'])->name('show');
                Route::get('/{customer}/edit',       [CustomerController::class, 'edit'])->name('edit');
                Route::put('/{customer}',            [CustomerController::class, 'update'])->name('update');
                Route::delete('/{customer}',         [CustomerController::class, 'destroy'])->name('destroy');
                Route::patch('/{customer}/toggle',   [CustomerController::class, 'toggle'])->name('toggle');
            });

        }); // end administration

    }); // end master-data

    Route::prefix('user-management')->name('user-management.')->group(function () {

        // ── Users ──────────────────────────────────────────────────
        Route::resource('users', UserController::class);

        // ── Roles ──────────────────────────────────────────────────
        Route::resource('roles', RoleController::class);
        Route::post('roles/{role}/sync-permissions', [RoleController::class, 'syncPermissions'])
            ->name('roles.sync-permissions');

        // ── Permissions ────────────────────────────────────────────
        Route::resource('permissions', PermissionController::class)
            ->except(['show']);
    });

    Route::prefix('data-management')->name('data-management.')->group(function () {

        Route::prefix('fleet-data')->name('fleet-data.')->group(function () {
            Route::resource('/', FleetController::class)
                ->parameters(['' => 'fleet'])
                ->names([
                    'index'   => 'index',
                    'create'  => 'create',
                    'store'   => 'store',
                    'show'    => 'show',
                    'edit'    => 'edit',
                    'update'  => 'update',
                    'destroy' => 'destroy',
                ]);

            Route::prefix('{fleet}/foto')->name('foto.')->group(function () {
                Route::delete('{photo}',            [FleetController::class, 'destroyPhoto'])->name('destroy');
                Route::patch('{photo}/set-primary', [FleetController::class, 'setPrimaryPhoto'])->name('set-primary');
                Route::patch('{photo}/update-meta',  [FleetController::class, 'updatePhotoMeta'])->name('update-meta');
            });

            Route::get('{fleet}/pdf', [FleetController::class, 'exportPdf'])->name('pdf');

            Route::prefix('api')->name('api.')->group(function () {
                Route::get('search',   [FleetController::class, 'apiSearch'])->name('search');
                Route::get('{fleet}',  [FleetController::class, 'apiShow'])->name('show');
            });
        });

        Route::prefix('sdm-management')->name('sdm-management.')->group(function () {

            // ── Data Sopir ─────────────────────────────────────
            Route::prefix('driver-data')->name('driver-data.')->group(function () {
                Route::resource('/', DriverController::class)
                    ->parameters(['' => 'driver'])
                    ->names([
                        'index'   => 'index',
                        'create'  => 'create',
                        'store'   => 'store',
                        'show'    => 'show',
                        'edit'    => 'edit',
                        'update'  => 'update',
                        'destroy' => 'destroy',
                    ]);

                Route::prefix('{driver}')->name('')->group(function () {
                    Route::delete('documents/{document}',       [DriverController::class, 'destroyDocument'])->name('documents.destroy');
                    Route::delete('certificates/{certificate}', [DriverController::class, 'destroyCertificate'])->name('certificates.destroy');
                });


            Route::prefix('api')->name('api.')->group(function () {
                    Route::get('generate-code', [DriverController::class, 'apiGenerateCode'])->name('generate-code');
                });
            });

            // ── Data Karyawan ──────────────────────────────────
            Route::prefix('employee-data')->name('employee-data.')->group(function () {
                Route::resource('/', EmployeeController::class)
                    ->parameters(['' => 'employee'])
                    ->names([
                        'index'   => 'index',
                        'create'  => 'create',
                        'store'   => 'store',
                        'show'    => 'show',
                        'edit'    => 'edit',
                        'update'  => 'update',
                        'destroy' => 'destroy',
                    ]);


            Route::prefix('api')->name('api.')->group(function () {
                    Route::get('generate-nrk', [EmployeeController::class, 'apiGenerateNrk'])->name('generate-nrk');
                });
            });
        });
    });
});

