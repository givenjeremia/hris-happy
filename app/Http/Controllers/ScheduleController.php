<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Shift;
use App\Models\Employee;
use App\Models\Posision;
use App\Models\Schedule;
use App\Models\Departement;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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
        $user = Auth::user();
        if($user->getRoleNames()->first() == 'admin') {
            $schedule = Schedule::orderBy('date','desc')->get();
        }
        else{
            $schedule = Schedule::where('employee_id',$user->employee->id)->orderBy('date','desc')->get();
        }
        $counter = 1;
        if (request()->ajax()) {
            $dataTable = Datatables::of($schedule)
                ->addColumn('No', function () use (&$counter) {
                    return $counter++;
                })
                ->addColumn('Employee', function ($item) {
                    return $item->employee->full_name;
                })
                ->addColumn('Shift', function ($item) {
                    return $item->shift->name;
                })
                ->addColumn('Date', function ($item) {
                    return Carbon::parse($item->date)->translatedFormat('d F Y');
                })
                ->addColumn('Desc', function ($item) {
                    return $item->desc;
                })
                ->addColumn('Created', function ($item) {
                    return Carbon::parse($item->created_at)->translatedFormat('d F Y, H:i');;
                })
                ->addColumn('Action', function ($item)  {
                    $encryptedIdString = "'" . $item->uuid . "'";
                    $button = 
                    '
                    <div class="dropdown">
                        <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                            class="btn btn-secondary w-100">Action</a>
                        <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow" style="left: 0px; right: inherit;">
                            <li><a href="#" onclick="updateData(' . $encryptedIdString . ')"  class="dropdown-item">Ubah</a></li>
                            <li><a href="#" onclick="deleteData(' . $encryptedIdString . ')"  class="dropdown-item">Hapus</a></li>
                        </ul>
                    </div>
                    ';
                    return $button;
                })->rawColumns(['No','Employee','Shift','Date','Desc','Created', 'Action']);
               
            return $dataTable->make(true);
        }
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
                'employee' => 'required',
                'shift' => 'required',
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

            $employee = Employee::where('uuid',$request->get('employee'))->firstOrFail();
            $shift = Shift::where('uuid',$request->get('shift'))->firstOrFail();

            $check = Schedule::where([['employee_id', $employee->id],['shift_id', $shift->id],['date', $request->get('date')]])->exists();
            if($check){
                return response()->json([
                    'status' => 'error',
                    'msg' => 'Schedule is already',
                ], 400);
            }
            
            $schedule = Schedule::create($request->except('_token', '_method'));
            $schedule->employee_id = $employee->id;
            $schedule->shift_id = $shift->id;
            $schedule->save();

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
    public function update(Request $request,$schedule)
    {
        try {
            $validator = Validator::make($request->all(), [
                'employee' => 'required',
                'shift' => 'required',
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
            $employee = Employee::where('uuid',$request->get('employee'))->firstOrFail();
            $shift = Shift::where('uuid',$request->get('shift'))->firstOrFail();
            
            $check = Schedule::where([['employee_id', $employee->id],['shift_id', $shift->id],['date', $request->get('date')]])->exists();
            
            if($check){
                return response()->json([
                    'status' => 'error',
                    'msg' => 'Schedule is already',
                ], 400);
            }
            
            $schedule= Schedule::firstWhere('uuid',$schedule);
            $schedule = $schedule->fill($request->except('_token', '_method'));
        
            $schedule->employee_id = $employee->id;
            $schedule->shift_id = $shift->id;
            $schedule->save();


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

    public function generateForm()
     {
         try {
            $departements = Departement::all();
            $shifts = Shift::all();
             
            return response()->json([
                 'status' => 'success', 
                 'msg' => view('page.schedules.modal.auto_generate', compact('departements', 'shifts'))->render()
             ], 200);
         } catch (\Throwable $th) {
             return response()->json(['status' => 'error', 'msg' => $th->getMessage()], 500);
         }
     }

  public function generateStore(Request $request)
    {
      
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'shift' => 'required',
                'departement' => 'required',
                'date' => 'required',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'msg' => 'Failed to Generate Schedule',
                    'valid' => $validator->errors()
                ], 400);
            }

            $departement = Departement::firstWhere('uuid', $request->get('departement'));
            $shift = Shift::firstWhere('uuid', $request->get('shift'));

            if (!$departement || !$shift) {
                throw new \Exception("Department or Shift not found.");
            }

            [$startDate, $endDate] = explode(' - ', $request->get('date'));
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);

            $positionIds = Posision::where('departement_id', $departement->id)->pluck('id');

            $employees = Employee::whereIn('posision_id', $positionIds)->pluck('id');

            $schedules = [];
            for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                foreach ($employees as $employeeId) {
                    $check = Schedule::where('employee_id', $employeeId)->where('shift_id', $shift->id)->where('date', $date->toDateString())->exists();
                    if (!$check) {
                        $data = [
                            'employee_id' => $employeeId,
                            'uuid' => Str::uuid(),
                            'shift_id' => $shift->id,
                            'date' => $date->toDateString(),
                            'desc' => $request->get('desc'),
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                        array_push($schedules, $data);
                    }
                }
            }
            
            Schedule::insert($schedules);

            DB::commit();
            return response()->json(['status' => 'success', 'msg' => 'Schedules generated successfully'], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'msg' => 'Failed to generate schedules', 'err' => $e->getMessage()], 500);
        }
    }


    public function indexCalender()
    {
        try {
            return view('page.schedules.calender');
        } catch (\Throwable $e) {
            # code...
        }
    }

    public function calenderData($start,$end)
    {
        try {
            $data = Schedule::whereBetween('date', [$start,$end])
            ->select(DB::raw('DATE(date) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            return response()->json(array('status' => 'success','data' => $data), 200);
        } catch (\Throwable $e) {
            # code...
        }
    }

    public function calenderDataDetail($date)
    {
        try {
            $data = Schedule::where('date',$date)->get();
            return response()->json(array('status' => 'success', 'msg' => view('page.schedules.modal.detail_kalender',compact('data','date'))->render()), 200);
        } catch (\Throwable $e) {
            # code...
        }
    }

}
