<?php

namespace App\Models;

use App\Models\Employee;
use Illuminate\Support\Str;
use App\Models\IncomeDetail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Income extends Model
{
    use HasFactory;

    public const STATUS_NO_PAYMENT= 'NO_PAYMENT';
    public const STATUS_PAYMENT = 'PAYMENT';
    public const STATUS_CANCELED = 'CANCELED';

    protected $fillable = [
        'status',
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

    public function incomeDetails()
    {
        return $this->hasMany(IncomeDetail::class);
    }

    public static function generateSalary($pegawai_id)
    {
        DB::beginTransaction();

        try {
            // Get Pegawai Data
            $employee = Employee::find($pegawai_id);
        
            // Get Basic Salary From Position
            $basic_salary = $employee->posision->salary;
        
            // Get Schedule, Presence, and vacation Counts
            $count_schedule = $employee->schedule()
                ->whereMonth('date', Carbon::now()->month)
                ->whereYear('date', Carbon::now()->year)
                ->count();
        
            $count_presensi = $employee->presence()
                ->whereMonth('date', Carbon::now()->month)
                ->whereYear('date', Carbon::now()->year)
                ->where('status', 'CLOCK_OUT')
                ->count();

            $vacations = $employee->vacation()
                ->whereMonth('start_date', Carbon::now()->month)
                ->whereYear('start_date', Carbon::now()->year)
                ->where('status', 'ACCEPTED')
                ->get()
                ->sum(function ($vacation) {
                    $startDate = Carbon::parse($vacation->start_date);
                    $endDate = Carbon::parse($vacation->end_date);
                    return $startDate->diffInDays($endDate) + 1;
                });
            $vacationCollection = $employee->vacation()
                ->whereMonth('start_date', Carbon::now()->month)
                ->whereYear('start_date', Carbon::now()->year)
                ->where('status', 'ACCEPTED')
                ->get();

            // Calculate OffDay
            $count_vacation = $vacations;

            // Calculate Allowances
            $allowance = [];
            $allowance_total = 0;
            $allowance_all = Allowance::all();
            foreach ($allowance_all as $key => $value) {
                $allowance[$value->name]['nominal'] = $value->nominal;
                $allowance[$value->name]['amount'] = $value->nominal * $count_schedule;
                $allowance[$value->name]['detail'] = '<ul class="p-0">
                    <li>Nominal : Rp. '.number_format($value->nominal, 0, ",", ".").'</li>
                    <li>Total Jadwal : '.$count_schedule.'</li>
                </ul> ';
                
                $allowance_total += $value->nominal * $count_schedule;
            }
    
        
            // Calculate Overtime
            $count_overtime = $employee->overtime()
                ->where('long_overtime', '>', 0)
                ->whereMonth('date', Carbon::now()->month)
                ->whereYear('date', Carbon::now()->year)
                ->sum(DB::raw('CAST(long_overtime AS DECIMAL(10,2))'));

        
            // Overtime
            $overtime_rate = ((int)$basic_salary / 173);
            $overtime_total = ( $overtime_rate/60)  * $count_overtime;
            $overtime_detail = '<ul class="p-0">
                <li>Gaji Dasar Perhari : Rp. '.number_format($overtime_rate, 0, ",", ".").'</li>
                <li>Lama Lembur Per Menit : '.$count_overtime.'</li>
            </ul> ';
        
            // BPJS
            $bpjs_amounts = [];
            $bpjs_total = 0; // Total BPJS
            $bpjs_all = Bpjs::all();
            foreach ($bpjs_all as $item) {
                $bpjs_amounts[$item->type]['total'] = $basic_salary * ($item->nominal/100);
                $bpjs_amounts[$item->type]['detail']= '<ul class="p-0">
                    <li>Gaji Perhari : Rp. '.number_format($overtime_rate, 0, ",", ".").'</li>
                    <li>BPJS : '.($item->nominal/100).'</li>
                </ul> ';
                $bpjs_total += $bpjs_amounts[$item->type]['total']; 
            }
        
            // Calculate PTKP (Allowances + Overtime + BPJS)
            $ptkp = $allowance_total + $overtime_total + $bpjs_total;
        
            // Safety Equipment
            $safety_equipment = $employee->safety_equipment ? $employee->safety_equipment : 0;
        


            // // Calculate Penalty
            // $penalty = 0;
            // foreach ($employee->schedule()->whereMonth('date', Carbon::now()->month)->get() as $schedule) {
            //     $presence = $employee->presence()->where('date', $schedule->date)->first();
            //     if (!$presence && !$vacationCollection->contains(function ($vacation) use ($schedule) {
            //         return $vacation->start_date <= $schedule->date && $vacation->end_date >= $schedule->date;
            //     })) {
            //         $penalty += 7000;
            //     }
            // }

            // // Calculate late penlaty
            // $late_penalty = 0;
            // foreach ($employee->presence()->whereMonth('date', Carbon::now()->month)->get() as $presensi) {
            //     if ($presensi->status === 'CLOCK_OUT' && $presensi->clock_in && $presensi->clock_in > $presensi->shift_start) {
            //         $late_minutes = $presensi->clock_in->diffInMinutes($presensi->shift_start);
            //         $late_penalty += floor($late_minutes / 30) * 5000;  // Denda 5000 per 30 mnt
            //     }
            // }


            $penalty = 0;
            $penalty_days = 0;
            $daily_salary = $basic_salary / $count_schedule;

            foreach ($employee->schedule()->whereMonth('date', Carbon::now()->month)->get() as $schedule){
                $presence = $employee->presence()->where('date', $schedule->date)->first();
                if (!$presence) {
                    $penalty_days += 1;
                }

            }
            $penalty = $daily_salary * $penalty_days;
            $penalty_detail = '<ul class="p-0">
                <li>Gaji Dasar Perhari : Rp. '.number_format($daily_salary, 0, ",", ".").'</li>
                <li>Ketidakhadiran : '.$penalty_days.'</li>
            </ul> ';;

            // Potongan PPH21 IF(R3>=4500000;(R3-4500000)*5%;0)
            $pph21 = $ptkp >= 4500000 ? (int)$ptkp * 0.05 : 0;
            $pph21_detail = '
            <p>Terhitung Jika Total PTKP Lebih Dari Rp. 4.500.000 Jika Tidak Akan Bernilai 0</p>
            <ul class="p-0">
                <li>PTKP (Total Tunjangan + Total Lembur + Total BPJS) : Rp. '.number_format($ptkp, 0, ",", ".").'</li>
                <li>Persen : 5%</li>
            </ul> ';;


            // Profit = Total Tunjangan * 4,5%
            $profit = $allowance_total * 0.8;
            $profit_detail = '<ul class="p-0">
                <li>Total Tunjangan : Rp. '.number_format($allowance_total, 0, ",", ".").'</li>
                <li>Persen : 4,5%</li>
            </ul> ';

            // Amout Salary Gaji pokok + BPJS + Safety + Profit - Potongan
            $amout_salary = $basic_salary + $bpjs_total + $safety_equipment + $profit - ($penalty + $pph21);

            ///////////////////////////////// Add In Database
            
            // In data
            $new_income = new Income();
            $new_income->uuid = Str::uuid();
            $new_income->nominal = $amout_salary;
            $new_income->period = now();
            $new_income->employee_id = $employee->id;
            $new_income->status = Income::STATUS_NO_PAYMENT;
            $new_income->save();

            // Add In Detail
            // //////////////////////////////// ADD IN

            // - lembur : total jam lembur karyawan berapa dan nominal upah per jam nya brapa
	        // - tunjangan : total hari dia mendapatkan tiap tunjangan dan nominal tunjangannya (makan dan transportasi)
	        // - potongan absensi : tidak masuk berapa hari dan nominal potongan per hari nya brapa
	        // - pph21 : ptkp yang didapatkan berapa Dibuat seperti dropdown atau sub menu, jadi ketika kategori gaji nya di klik muncul detail per kategorinya (lembur, tunjangan, potongan absensi, dan pph21)

            $in_detail = [
                'Gaji Pokok' => [
                    $basic_salary,'-'
                ],
                'Profit' => [
                    $profit,$profit_detail
                ],
                'Lembur' => [
                    $overtime_total,$overtime_detail 
                ],
            ];
            // Add Allowances to in_detail
            foreach ($allowance as $name => $details) {      
                $in_detail['Tunjangan '.$name] = [
                    $details['amount'],$details['detail']
                ];
            }

            // Add Allowances to in_detail
            foreach ($bpjs_amounts as $type => $amount) {
                $in_detail['BPJS ' . $type] = [
                    $amount['total'],$amount['detail']
                ];
            }

            foreach ($in_detail as $key => $value) {
                $new_detail_income = new IncomeDetail();
                $new_detail_income->uuid = Str::uuid();
                $new_detail_income->type = 'IN';
                $new_detail_income->desc = $value[1];
                $new_detail_income->nominal = $value[0];
                $new_detail_income->category = $key;
                $new_detail_income->income_id = $new_income->id;
                $new_detail_income->save();
            }

            // //////////////////////////////// ADD OUT
            $out_detail = [
                'Absensi' => [
                    $penalty, $penalty_detail
                ],
                'PPH21' => [
                    $pph21,$pph21_detail
                ],
            ];

            foreach ($out_detail as $key => $value) {
                $new_detail_income = new IncomeDetail();
                $new_detail_income->uuid = Str::uuid();
                $new_detail_income->type = 'OUT';
                $new_detail_income->desc = $value[1];

                $new_detail_income->nominal = $value[0];
                $new_detail_income->category = $key;
                $new_detail_income->income_id = $new_income->id;
                $new_detail_income->save();
            }

            DB::commit();

            return $new_income;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
    

}
