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
        
            Route::resource('vacations', VacationController::class);
        
            Route::resource('shifts', ShiftController::class);
       

    });
    
    




});

