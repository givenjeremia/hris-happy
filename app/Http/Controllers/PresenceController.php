<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Overtime;
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
            $user = Auth::user();
            if($user->getRoleNames()->first() == 'admin') {
                return view('page.presence.admin.index');
            }
            else{
                return view('page.presence.index');
            }
            
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

    public function tablePegawaiAbsen()
    {
        $user = Auth::user();
        $data_day_now_schedule = Schedule::where('date', '>=', Carbon::today())
            ->where('date', '<=', Carbon::today()->addDays(4))
            ->orderBy('date', 'desc')
            ->get();
        $counter = 1;
        if (request()->ajax()) {
            $dataTable = Datatables::of($data_day_now_schedule)
                ->addColumn('No', function () use (&$counter) {
                    return $counter++;
                })
                ->addColumn('Employee', function ($item) {
                    return $item->employee->full_name;
                })
                ->addColumn('Date', function ($item) {
                    return Carbon::parse($item->date)->translatedFormat('d F Y');
                })
                ->addColumn('Shift', function ($item) {
                    return $item->shift->name;
                })
                ->addColumn('Time In', function ($item) {
                    return $item->shift->time_in;
                })
                ->addColumn('Time Out', function ($item) {
                    return  $item->shift->time_out;
                })
                ->addColumn('Status Absen', function ($item) {
                    $status_in = '-';
                    $status_out = '-';
                    $presence =  Presence::where('employee_id', $item->employee->id)
                    ->whereDate('date', Carbon::today())->first();

                    if($presence){
                        $status = $presence->status;
                        if($status == 'CLOCK_IN'){
                            $status_in = 'Sudah';
                            $status_out = '-';
                        }
                        if($status == 'CLOCK_OUT'){
                            $status_in = 'Sudah';
                            $status_out = 'Sudah';
                        }
                    }

                    return '<ul class="m-0 px-3">
                        <li>IN : '.$status_in.'</li>
                        <li>OUT : '.$status_out.'</li>
                    </ul>';
                })
                ->addColumn('Action', function ($item) {
                    $encryptedIdString = "'" .  $item->employee->uuid. "'";
                    $status_in = '';
                    $status_out = '';
                    $presence =  Presence::where('employee_id', $item->employee->id)
                    ->whereDate('date', Carbon::today())->first();

                    if($presence){
                        $status = $presence->status;
                        if($status == 'CLOCK_IN'){
                            $status_out =  '<li><a href="#" onclick="updateAbsen(' . $encryptedIdString . ','. "'Clock Out Pegawai'".')"  class="dropdown-item">CLOCK OUT</a></li>';
                        }
                    }
                    else{
                        $status_in = '<li><a href="#" onclick="updateAbsen(' . $encryptedIdString . ','. "'Clock In Pegawai'".')"  class="dropdown-item">CLOCK IN</a></li>';
                    }
                    $button = 
                    '
                    <div class="dropdown">
                        <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                            class="btn btn-secondary w-100">Action</a>
                        <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow" style="left: 0px; right: inherit;">
                            
                            '.$status_in.'
                            '.$status_out.'
                        </ul>
                    </div>
                    ';
                    // return  $button;
                    return $presence ? ( $presence->status == 'CLOCK_OUT' ? '-' : $button ) : $button;
                })
                ->rawColumns(['No','Employee','Date','Shift','Time In','Time Out','Status Absen','Action']);
               
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

            // Ambil semua jadwal untuk hari ini berdasarkan karyawan
            $schedules = Schedule::where('employee_id', $user->employee->id)
            ->whereDate('date', $date_now)
            ->with('shift') // Relasi ke tabel shift
            ->get();

            if ($schedules->isEmpty()) {
                return response()->json([
                    'status' => 'error', 
                    'msg' => 'Gagal Clock Out Presences, You Not Schedule'
                ], 200);
            }

            // Waktu sekarang
            $time_now = Carbon::now();

            // Variabel untuk menyimpan shift yang aktif
            $currentShift = null;

            // Periksa apakah waktu sekarang melewati semua `time_out` shift
            foreach ($schedules as $schedule) {
                $shift = $schedule->shift;
           

                if ($shift) {
                    $time_out = Carbon::createFromFormat('H:i:s', $shift->time_out);
                    $time_in = Carbon::createFromFormat('H:i:s', $shift->time_in);

                    // Tangani jika shift melewati tengah malam
                    if ($time_out->lessThan($time_in)) {
                        $time_out->addDay();
                    }

                    // Periksa apakah waktu sekarang melewati waktu shift
                    if ($time_now->gt($time_out)) {
                        $currentShift = $shift; // Tetapkan shift saat ini
                    }
                }
            }

            // Jika tidak ada shift yang waktu kerjanya selesai, tolak permintaan Clock Out
            if (!$currentShift) {
                return response()->json([
                    'status' => 'error',
                    'msg' => 'Gagal Clock Out Presences, You Shift Dont End'
                ], 200);
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

            if ($presence->status == 'CLOCK_OUT'){
                $out = $currentShift->time_out;
                $now_time = Carbon::now();
                if ($now_time->greaterThan($out)) {
                    $selisih_menit = $now_time->diffInMinutes($out);

                    // Add Overtime
                    $overtime = new Overtime();
                    $overtime->employee_id = $presence->employee_id;
                    $overtime->date = $now_time;
                    $overtime->long_overtime = $selisih_menit;
                    $overtime->information = 'Pegawai '.$presence->employee->full_name.' Lembur Selama '.$selisih_menit.' Menit Terhitung Dari Waktu Time Out Shift '.$out;
                    $overtime->save();
    
                }
            }

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


    public function updateAbsensiPegawaiAdmin(Request $request, $employee)
    {
        try {
            // Set Before
            $date_now = Carbon::now()->format('Y-m-d'); 
            $time_now = Carbon::now();
            // Get Employee
            $employee = Employee::with('client','presence','schedule')->where('uuid',$employee)->first();
            $presence_today = $employee->presence()->whereDate('date',  $time_now)->first();

            // Jika Belum Presense Today Lakukan Clock IN Jika Sudah Lakukan Check Out
            if(!$presence_today){
              
                $presence = new Presence();
                $presence->employee_id = $employee->id;
                $presence->latitude_in = $employee->client->latitude;
                $presence->longitude_in = $employee->client->longitude;
                $presence->office = $employee->client->uuid;
                $presence->time_in = $time_now; 
                $presence->time_out = '00:00:00';
                $presence->date = $date_now;
                $presence->status = 'CLOCK_IN';
                $presence->save();
            }
            else{
                $presence_today->status = 'CLOCK_OUT';
                $presence_today->time_out =  $time_now;
                $presence_today->latitude_out = $employee->client->latitude;
                $presence_today->longitude_out = $employee->client->longitude;
                $presence_today->save();
            }

            return response()->json(['status' => 'success', 'msg' => 'Success Update Presence'], 200);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'msg' => 'Gagal Update Presensi', 'err' => $e->getMessage()], 500);
        }
    }
}
