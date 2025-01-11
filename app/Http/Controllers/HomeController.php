<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Presence;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Location\Facades\Location;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
 
        $user = Auth::user();
        if($user->getRoleNames()->first() == 'admin') {
            return view('page.dashboard.admin');
        }
        else{
            $data_3_day_schedule = Schedule::where('employee_id', $user->employee->id)
                ->where('date', '>=', Carbon::today())
                ->where('date', '<=', Carbon::today()->addDays(4))
                ->orderBy('date', 'desc')
                ->get();

            return view('page.dashboard.employee',compact('data_3_day_schedule'));
        }
      
    }

    public function updateCurrentLocation(Request $request)
    {
        
        try {
            // Get Lat Long Current Location
            $latitude = $request->get('latitude');
            $longitude = $request->get('longitude');

            // Get Lat Long Client
            $user = Auth::user();
            $client = $user->employee->client;
            $client_lat = $client->latitude;
            $client_long = $client->longitude;

            // Get Distance Client To Current Location

            $distance = Presence::convertToDistance([$client_lat, $client_long],[$latitude, $longitude]);

            $data = $client->name;
            $render_button = '';

            if($distance > 5){
                $data .= ' Anda Diluar Jangkauan '.$distance.' KM';

            }
            else{
                // Render Button Axnd Check 
                // Check schedule jika  1 hari lebih dari 1

              // Ambil semua jadwal today
                $schedules = Schedule::with('shift')
                ->where('employee_id', $user->employee->id)
                ->whereDate('date', Carbon::today())
                ->get();

                if ($schedules->count() > 1) {
                // Waktu sekarang
                $now = Carbon::now();

                // Temukan jadwal berdasarkan waktu sekarang
                $currentSchedule = $schedules->filter(function ($schedule) use ($now) {
                    $shift = $schedule->shift;
                    if ($shift) {
                        $timeIn = Carbon::parse($shift->time_in);
                        $timeOut = Carbon::parse($shift->time_out);

                        // Tangani jika shift melewati tengah malam
                        if ($timeOut->lessThan($timeIn)) {
                            $timeOut->addDay();
                        }

                        return $now->between($timeIn, $timeOut);
                    }
                    return false;
                })->first();

                if ($currentSchedule) {
                    // Jika ada jadwal aktif, cari presensi berdasarkan waktu dan jadwal
                    $presense = Presence::where('employee_id', $user->employee->id)
                        ->whereDate('date', Carbon::today())
                        ->where(function ($query) use ($currentSchedule) {
                            $shift = $currentSchedule->shift;
                            $query->whereTime('time_in', '>=', $shift->time_in)
                                ->whereTime('time_in', '<=', $shift->time_out);
                        })
                        ->where('status', '<>', 'CLOCK_OUT') // Pastikan bukan presensi yang sudah Clock Out
                        ->first();
                } else {
                    // Jika tidak ada jadwal aktif, ambil presensi terakhir
                    $presense = Presence::where('employee_id', $user->employee->id)
                        ->whereDate('date', Carbon::today())
                        ->where('status', '<>', 'CLOCK_OUT') // Pastikan bukan presensi yang sudah Clock Out
                        ->orderBy('created_at', 'desc')
                        ->first();
                }
                } else {
                // Jika hanya ada 1 jadwal, ambil presensi
                $presense = Presence::where('employee_id', $user->employee->id)
                    ->whereDate('date', Carbon::today())
                    ->where('status', '<>', 'CLOCK_OUT') // Pastikan bukan presensi yang sudah Clock Out
                    ->first();
                }

                // Jika semua presensi sudah Clock Out, maka set presense ke null
                if ($presense && $presense->status === 'CLOCK_OUT') {
                $presense = null;
                }

                // Cek jika semua presensi hari ini sudah Clock Out
                $allClockedOut = Presence::where('employee_id', $user->employee->id)
                ->whereDate('date', Carbon::today())
                ->count() > 0 && Presence::where('employee_id', $user->employee->id)
                ->whereDate('date', Carbon::today())
                ->where('status', '<>', 'CLOCK_OUT')
                ->count() === 0;


                $render_button = view('page.dashboard.button_absen',compact('presense','allClockedOut'))->render();
                $data .= ' Anda Dalam Jangkauan '.$distance.' KM';
            }

            return response()->json([
                'status' => 'success',
                'msg' => 'Location data diterima',
                'data' => $data,
                'render_button' => $render_button
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    

}
