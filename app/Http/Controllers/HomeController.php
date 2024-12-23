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
                ->where('date', '<=', Carbon::today()->addDays(3))
                ->orderBy('date', 'asc')
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
                // Render Button And Check 
                $presense = Presence::where('employee_id', $user->employee->id)->whereDate('date', Carbon::today())->first();
                $render_button = view('page.dashboard.button_absen',compact('presense'))->render();
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
