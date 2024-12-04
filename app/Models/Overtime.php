<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Overtime extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'employee_id',
        'date',
        'long_overtime',
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
    
}
