<?php

use App\Models\Shift;
use App\Models\Contract;
use App\Models\Employee;
use App\Models\Overtime;
use App\Models\Posision;
use App\Models\Presence;
use App\Models\Vacation;
use App\Models\Departement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\PosisionController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\VacationController;
use App\Http\Controllers\DepartementController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['web','auth'])->group(function(){
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/update-current-location', [App\Http\Controllers\HomeController::class, 'updateCurrentLocation'])->name('update.current.location');



    Route::prefix('master-data')->group(function () {
        Route::resource('clients', ClientController::class);
        Route::get('clients-table', [ClientController::class ,'tableDataAdmin'])->name('clients.table');
    
    
    
        Route::resource('contracts', ContractController::class);
        Route::get('contracts-table', [ContractController::class ,'tableDataAdmin'])->name('contracts.table');
    
    
    
        Route::resource('departements', DepartementController::class);
        Route::get('departements-table', [DepartementController::class ,'tableDataAdmin'])->name('departements.table');
    
        Route::resource('posisions', PosisionController::class);
        Route::get('posisions-table', [PosisionController::class ,'tableDataAdmin'])->name('posisions.table');

    });



    Route::prefix('personnel')->group(function () {
        
        Route::resource('employee', EmployeeController::class);
        Route::get('employee-table', [EmployeeController::class ,'tableDataAdmin'])->name('employee.table');
        
        Route::resource('overtimes', OvertimeController::class);    
        
        Route::resource('presences', PresenceController::class);
        Route::get('presences-table', [PresenceController::class,'table'])->name('presences.table');

        // Vacation
        Route::prefix('vacations')->name('vacations.')->group(function () {
            Route::get('/', [VacationController::class, 'index'])->name('index');
            Route::get('/table', [VacationController::class, 'tableDataAdmin'])->name('tableData');
            Route::post('/', [VacationController::class, 'store'])->name('store');
            Route::get('/{vacation}', [VacationController::class, 'show'])->name('show'); 
            Route::put('/{vacation}', [VacationController::class, 'update'])->name('update');
            Route::delete('/{vacation}', [VacationController::class, 'destroy'])->name('destroy');
        });

        Route::resource('shifts', ShiftController::class);
        Route::get('shifts-table', [ShiftController::class ,'tableDataAdmin'])->name('shifts.table');

        // Schedule 
        Route::prefix('schedules')->name('schedules.')->group(function () {
            Route::get('/', [ScheduleController::class, 'index'])->name('index');
            Route::get('/create', [ScheduleController::class, 'create'])->name('create'); 
            Route::post('/', [ScheduleController::class, 'store'])->name('store'); 
            Route::get('/{id}/edit', [ScheduleController::class, 'edit'])->name('edit');
            Route::put('/{id}', [ScheduleController::class, 'update'])->name('update'); 
            Route::delete('{id}', [ScheduleController::class, 'destroy'])->name('destroy'); 
            Route::get('schedule-table', [ScheduleController::class, 'table'])->name('table');

            Route::get('/generate-form', [ScheduleController::class, 'generateForm'])->name('generate.form'); 
            Route::post('/generate-store', [ScheduleController::class, 'generateStore'])->name('generate.store'); 

        });


    });
    
    




});

