<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Presence extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'employee_id',
        'latitude_in',
        'longitude_in',
        'latitude_out',
        'longitude_out',
        'office',
        'time_in',
        'time_out',
        'date',
        'status',
        'information',
      
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid(); 
        });
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public static function convertToDistance($latLongOne, $latLongTwo){

        $latitude1 =  (float)$latLongOne[0];
        $longitude1 = (float) $latLongOne[1];
        $latitude2 =  (float)$latLongTwo[0];
        $longitude2 = (float)$latLongTwo[1];

        // Radius of the Earth in kilometers (use 3958.8 for miles)
        $earthRadius = 6371;

        $latFrom = deg2rad($latitude1);
        $lonFrom = deg2rad($longitude1);
        $latTo = deg2rad($latitude2);
        $lonTo = deg2rad($longitude2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos($latFrom) * cos($latTo) *
            sin($lonDelta / 2) * sin($lonDelta / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return round($distance, 2);

    }
    
}
