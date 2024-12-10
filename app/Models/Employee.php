<?php

namespace App\Models;

use App\Models\Client;
use App\Models\Posision;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;
    use SoftDeletes;



    protected $fillable = [
        'client_id',
        'posision_id',
        'nik',
        'full_name',
        'date_of_birth',
        'address',
        'bank_account_name',
        'bank_account_number',
        'phone_number',
        'code_ptkp',
        'safety_equipment'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid(); 
        });
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function posision()
    {
        return $this->belongsTo(Posision::class);
    }


    public function presence()
    {
        return $this->hasMany(Presence::class);
    }

    public function overtime()
    {
        return $this->hasMany(Overtime::class);
    }

    public function schedule()
    {
        return $this->hasMany(Schedule::class);
    }

    public function vacation()
    {
        return $this->hasMany(Vacation::class);
    }

}
