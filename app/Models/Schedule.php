<?php

namespace App\Models;

use App\Models\Employee;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'employee_id', 'shift_id', 'date', 'desc'
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

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
