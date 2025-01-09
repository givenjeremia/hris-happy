<?php

use App\Models\Bpjs;
use App\Models\Shift;
use App\Models\Income;
use App\Models\Contract;
use App\Models\Employee;
use App\Models\Overtime;
use App\Models\Posision;
use App\Models\Presence;
use App\Models\Vacation;
use App\Models\Departement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BpjsController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\PosisionController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\VacationController;
use App\Http\Controllers\AllowanceController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Auth\ResetPasswordController;

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

        Route::resource('allowance', AllowanceController::class);
        Route::resource('bpjs', BpjsController::class);

        Route::resource('shifts', ShiftController::class);
        Route::get('shifts-table', [ShiftController::class ,'tableDataAdmin'])->name('shifts.table');

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
            Route::get('/table', [VacationController::class, 'tableData'])->name('tableData');
            Route::post('/', [VacationController::class, 'store'])->name('store');
            Route::get('/{vacation}', [VacationController::class, 'show'])->name('show'); 
            Route::put('/{vacation}', [VacationController::class, 'update'])->name('update');
            Route::delete('/{vacation}', [VacationController::class, 'destroy'])->name('destroy');


            Route::put('/{vacation}/status', [VacationController::class, 'updateStatus'])->name('update.status');
        });

       

    


    });

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

        // Calender
        Route::get('/calender', [ScheduleController::class ,'indexCalender'])->name('index.calender');
        Route::get('/calender-data/{start}/{end}', [ScheduleController::class ,'calenderData'])->name('data.calender');
        Route::get('/calender-data-detail/{date}', [ScheduleController::class ,'calenderDataDetail'])->name('detail.calender');

    });

    Route::resource('income', IncomeController::class);
    Route::get('income-generate/generate-salary-all', [IncomeController::class, 'generateGajiAll'])->name('income.generate.salary'); 
    Route::get('/generate-pdf/{incomeId}', [IncomeController::class, 'generatePayslipPDF'])->name('generate-pdf');

    Route::prefix('reports')->group(function () {
        Route::get('/presences', [ReportController::class, 'presenceReport'])->name('reports.presences');
        Route::get('/presence/table', [ReportController::class, 'presenceTable'])->name('reports.presence.table');
        Route::get('/presence/pdf', [ReportController::class, 'presenceGeneratePDF'])->name('reports.presence.pdf');

        Route::get('/overtime', [ReportController::class, 'overtimeReport'])->name('reports.overtime');
        Route::get('/overtime/table', [ReportController::class, 'overtimeTable'])->name('reports.overtime.table');
        Route::get('/overtime/pdf', [ReportController::class, 'overtimeGeneratePDF'])->name('reports.overtime.pdf');


        Route::get('/vacation', [ReportController::class, 'vacationReport'])->name('reports.vacation');
        Route::get('/vacation/table', [ReportController::class, 'vacationTable'])->name('reports.vacation.table');
        Route::get('/vacation/pdf', [ReportController::class, 'vacationGeneratePDF'])->name('reports.vacation.pdf');

        Route::get('/income', [ReportController::class, 'incomeReport'])->name('reports.income');
    });
    

    Route::get('/reset-password', [ResetPasswordController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('/reset-password-store', [ResetPasswordController::class, 'reset'])->name('password.update');


});

