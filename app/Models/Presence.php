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
    
}
