<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Posision extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'positions';

    protected $fillable = [
        'departement_id',
        'name',
        'salary',
      
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid(); 
        });
    }
    

}
