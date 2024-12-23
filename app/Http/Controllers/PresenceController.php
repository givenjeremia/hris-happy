<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\Presence;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PresenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return view('page.presence.index');
        } catch (\Throwable $e) {
            # code...
        }
    }

    public function table()
    {
        $user = Auth::user();
        if($user->getRoleNames()->first() == 'admin') {
            $presense = Presence::orderBy('id','desc')->get();
        }
        else{
            $presense = Presence::where('employee_id',$user->employee->id)->get();
        }
        $counter = 1;
        if (request()->ajax()) {
            $dataTable = Datatables::of($presense)
                ->addColumn('No', function () use (&$counter) {
                    return $counter++;
                })
                ->addColumn('Employee', function ($item) {
                    return $item->employee->full_name;
                })
                ->addColumn('Date', function ($item) {
                    return $item->date;
                })
                ->addColumn('Lat In', function ($item) {
                    return $item->latitude_in;
                })
                ->addColumn('Long In', function ($item) {
                    return $item->longitude_in;
                })
                ->addColumn('Time In', function ($item) {
                    return $item->time_in;
                })
                ->addColumn('Lat Out', function ($item) {
                    return $item->latitude_out;
                })
                ->addColumn('Long Out', function ($item) {
                    return $item->longitude_out;
                })
                ->addColumn('Time Out', function ($item) {
                    return $item->time_out;
                })
                ->addColumn('Status', function ($item) {
                    return $item->status;
                })
                ->addColumn('Information', function ($item) {
                    return $item->information;
                })->rawColumns(['No','Employee','Date','Lat In','Long In','Time In', 'Lat Out','Long Out','Time Out','Status','Information']);
               
            return $dataTable->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            # code...
        } catch (\Throwable $e) {
            # code...
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'long' => 'required',
                'lat' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'msg' => 'Failed Clock In Presense',
                    'err' => 'Check Input',
                    'valid' => $validator->errors()
                ], 400);
            }
            // Get User
            $user = Auth::user();

            $latitude = $request->get('lat');
            $longitude = $request->get('long');

            // Cek Terlebih Dahulu Apakah User Memiliki Shejule Hari Ini dan Shift Seakrang 
            $date_now = Carbon::now()->format('Y-m-d'); 
            $time_now = Carbon::now();


            $schedule = Schedule::where('date',$date_now)->first();

            if(!$schedule){
                return response()->json(['status' => 'error', 'msg' => 'Gagal Clock In Presences, You Not Schedule'], 200);
            }

            $shift = Shift::find($schedule->shift_id);
            if (!$time_now->gt(Carbon::createFromFormat('H:i:s', $shift->time_in))) {
                return response()->json(['status' => 'error', 'msg' => 'Gagal Clock In Presences, You Shift Dont Start'], 200);
            }

            //Client Data
            $client = $user->employee->client;
            $client_lat = $client->latitude;
            $client_long = $client->longitude;

            // Get Distance Client To Current Location
            $distance = Presence::convertToDistance([$client_lat, $client_long],[$latitude, $longitude]);

     
            $presence = new Presence();
            $presence->employee_id = $user->employee->id;
            $presence->latitude_in =$latitude;
            $presence->longitude_in = $longitude;
            $presence->office = $client->uuid;
            $presence->time_in = $time_now; 
            $presence->time_out = '00:00:00';
            $presence->date = $date_now;
            $presence->status = 'CLOCK_IN';

            $informasi = 'Pegawai Masuk Jam '.now()->format('H:i:s'). ' Pada Area '. $distance.' KM';

            $presence->information = $informasi;
            $presence->save();

            return response()->json(['status' => 'success', 'msg' => 'Success Presence Clock In'], 201);

        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'msg' => 'Failed Presence Clock In', 'err' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Presence $presence)
    {
        try {
            # code...
        } catch (\Throwable $e) {
            # code...
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Presence $presence)
    {
        try {
            # code...
        } catch (\Throwable $e) {
            # code...
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $presence)
    {
        try {
            $validator = Validator::make($request->all(), [
                'long' => 'required',
                'lat' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'msg' => 'Failed Clock In Presense',
                    'err' => 'Check Input',
                    'valid' => $validator->errors()
                ], 400);
            }
            // Get User
            $user = Auth::user();

            $latitude = $request->get('lat');
            $longitude = $request->get('long');

            // Cek Terlebih Dahulu Apakah User Memiliki Shejule Hari Ini dan Shift Seakrang 
            $date_now = Carbon::now()->format('Y-m-d'); 
            $time_now = Carbon::now();


            $schedule = Schedule::where('date',$date_now)->first();

            if(!$schedule){
                return response()->json(['status' => 'error', 'msg' => 'Gagal Clock Out Presences, You Not Schedule'], 200);
            }

            $shift = Shift::find($schedule->shift_id);
            if (!$time_now->gt(Carbon::createFromFormat('H:i:s', $shift->time_out))) {
                return response()->json(['status' => 'error', 'msg' => 'Gagal Clock Out Presences, You Shift Dont End'], 200);
            }

            //Client Data
            $client = $user->employee->client;
            $client_lat = $client->latitude;
            $client_long = $client->longitude;

            // Get Distance Client To Current Location
            $distance = Presence::convertToDistance([$client_lat, $client_long],[$latitude, $longitude]);

     
            $presence = Presence::firstWhere('uuid',$presence);
            $presence->latitude_out =$latitude;
            $presence->longitude_out = $longitude;
            $presence->time_out =  $time_now; 
            $presence->date = $date_now;
            $presence->status = 'CLOCK_OUT';

            $informasi = 'Pegawai Keluar Jam '.now()->format('H:i:s'). ' Pada Area '. $distance.' KM';

            $presence->information =  $presence->information.' '.$informasi;
            $presence->save();

            return response()->json(['status' => 'success', 'msg' => 'Success Presence Clock Out'], 200);

        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'msg' => 'Failed Presence Clock Out', 'err' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($presence)
    {
        try {
            $presence = Presence::firstWhere('uuid', $presence);
            $presence->delete();
            return response()->json(['status' => 'success', 'msg' => 'Success Delete Presence'], 200);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'msg' => 'Success Delete Presence', 'err' => $e->getMessage()], 500);
        }
    }
}
