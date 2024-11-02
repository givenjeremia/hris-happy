<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
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
            # code...
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return response()->json(array('status' => 'success', 'msg' => view('page.schedules.modal.create' )->render()), 200);
        } catch (\Throwable $th) {
            return response()->json(array('status' => 'error', 'msg' =>$th->getMessage() ), 500);
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
                'date' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(array('status' => 'error','msg' => 'Failed Create Schedule','err'=>'Check Input','valid'=>$validator->errors()), 400);
            }
            else{
                $schedule = Schedule::create($request->except('_token', '_method'));
                $schedule->save();
                return response()->json(array('status' => 'success','msg' => 'Success Create Schedule'), 201);

            }
        } catch (\Throwable $e) {
            return response()->json(array('status' => 'error','msg' => 'Failed Create Schedule','err'=>$e->getMessage()), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($schedule)
    {
        try {
            $schedule = Schedule::firstWhere('uuid',$schedule);
            return response()->json(array('status' => 'success', 'msg' => view('page.schedules.modal.update',compact('schedule'))->render()), 200);
        } catch (\Throwable $th) {
            return response()->json(array('status' => 'error', 'msg' =>$th->getMessage() ), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        //
    }


    public function generate(Schedule $schedule)
    {
        //
    }


}
