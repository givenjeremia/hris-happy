<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Employee;
use App\Models\Shift;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return view('page.schedules.index');
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'msg' => $e->getMessage()], 500);
        }
    }

    public function table()
{
    // Mengambil data Schedule dan menggabungkan data relasi (employee dan shift)
    $schedules = Schedule::with(['employee', 'shift'])->select(['id', 'uuid', 'employee_id', 'shift_id', 'date', 'desc', 'created_at', 'updated_at']);
    
    // Menggunakan DataTables untuk format respons JSON yang dibutuhkan
    return DataTables::of($schedules)
        ->addIndexColumn() // Menambahkan nomor urut otomatis
        ->addColumn('employee_name', function ($row) {
            return $row->employee->name; // Mendapatkan nama dari relasi employee
        })
        ->addColumn('shift_name', function ($row) {
            return $row->shift->name; // Mendapatkan nama dari relasi shift
        })
        ->addColumn('action', function ($row) {
            // Tombol Edit dan Hapus
            return '
                <button data-uuid="'.$row->uuid.'" class="btn btn-sm btn-warning edit-btn">Edit</button>
                <button data-uuid="'.$row->uuid.'" class="btn btn-sm btn-danger delete-btn">Delete</button>
            ';
        })
        ->rawColumns(['action'])
        ->make(true);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            // Load data pegawai dan shift untuk form
            $employees = Employee::all();
            $shifts = Shift::all();
            
            return response()->json([
                'status' => 'success', 
                'msg' => view('page.schedules.modal.create', compact('employees', 'shifts'))->render()
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'msg' => $th->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'employee' => 'required|exists:employees,id',
                'shift' => 'required|exists:shifts,id',
                'date' => 'required|date',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'msg' => 'Failed Create Schedule',
                    'err' => 'Check Input',
                    'valid' => $validator->errors()
                ], 400);
            }

            $schedule = Schedule::create([
                'employee_id' => $request->employee,
                'shift_id' => $request->shift,
                'date' => $request->date,
                'desc' => $request->desc,
            ]);

            return response()->json(['status' => 'success', 'msg' => 'Success Create Schedule'], 201);

        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'msg' => 'Failed Create Schedule', 'err' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        try {
            return response()->json(['status' => 'success', 'data' => $schedule], 200);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'msg' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($schedule)
    {
        try {
            $schedule = Schedule::firstWhere('uuid', $schedule);
            $employees = Employee::all();
            $shifts = Shift::all();

            return response()->json([
                'status' => 'success', 
                'msg' => view('page.schedules.modal.update', compact('schedule', 'employees', 'shifts'))->render()
            ], 200);

        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'msg' => $th->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
        try {
            $validator = Validator::make($request->all(), [
                'employee' => 'required|exists:employees,id',
                'shift' => 'required|exists:shifts,id',
                'date' => 'required|date',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'msg' => 'Failed Update Schedule',
                    'err' => 'Check Input',
                    'valid' => $validator->errors()
                ], 400);
            }

            $schedule->update([
                'employee_id' => $request->employee,
                'shift_id' => $request->shift,
                'date' => $request->date,
                'desc' => $request->desc,
            ]);

            return response()->json(['status' => 'success', 'msg' => 'Success Update Schedule'], 200);

        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'msg' => 'Failed Update Schedule', 'err' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        try {
            $schedule->delete();

            return response()->json(['status' => 'success', 'msg' => 'Schedule Deleted Successfully'], 200);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'msg' => 'Failed to delete Schedule', 'err' => $e->getMessage()], 500);
        }
    }

    /**
     * Generate new schedules for all employees within a specific criteria.
     */
    public function generate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'shift' => 'required|exists:shifts,id',
                'date' => 'required|date',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'msg' => 'Failed to Generate Schedule',
                    'valid' => $validator->errors()
                ], 400);
            }

            // Retrieve all employees and assign them the specified shift and date
            $employees = Employee::all();

            foreach ($employees as $employee) {
                Schedule::create([
                    'employee_id' => $employee->id,
                    'shift_id' => $request->shift,
                    'date' => $request->date,
                    'desc' => $request->desc,
                ]);
            }

            return response()->json(['status' => 'success', 'msg' => 'Schedules generated successfully'], 201);

        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'msg' => 'Failed to generate schedules', 'err' => $e->getMessage()], 500);
        }
    }
}
