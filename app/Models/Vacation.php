<?php

namespace App\Models;

use App\Models\Employee;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vacation extends Model
{
    use HasFactory;

    use SoftDeletes;

    public const STATUS_PENDING = 'PENDING';
    public const STATUS_ACCEPTED = 'ACCEPTED';
    public const STATUS_REJECTED = 'REJECTED';
    public const STATUS_CANCELED = 'CANCELED';

    private const VALID_STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_ACCEPTED,
        self::STATUS_REJECTED,
        self::STATUS_CANCELED,
    ];

    public static function isValidStatus(string $status): bool
    {
        return in_array($status, self::VALID_STATUSES, true) ? $status : null;
    }


    protected $fillable = [
        'employee_id',
        'start_date',
        'end_date',
        'subject',
        'information',
        'status'
      
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
