<?php

namespace App\Http\Controllers;

use App\Models\Vacation;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class VacationController extends Controller
{
    /**
     * Display a listing of the resource for the logged-in user.
     */
    public function index()
    {
        try {
            return view('page.vacation.index');
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'msg' => 'Failed to load page', 'err' => $e->getMessage()], 500);
        }
    }

    /**
     * Get vacations data for the logged-in user or admin.
     */
    public function tableData()
    {
        $user = Auth::user();
        $counter = 1;

        if ($user->getRoleNames()->first() == 'admin') {
            $vacations = Vacation::with('employee')->orderBy('id', 'desc')->get();
        } else {
            $vacations = Vacation::where('employee_id', $user->employee->id)->orderBy('id', 'desc')->get();
        }

        if (request()->ajax()) {
            $dataTable = DataTables::of($vacations)
                ->addColumn('No', function () use (&$counter) {
                    return $counter++;
                })
                ->addColumn('Employee Name', function ($item) {
                    return $item->employee->full_name;
                })
                ->addColumn('Start Date', function ($item) {
                    return Carbon::parse($item->start_date)->format('Y-m-d');
                })
                ->addColumn('End Date', function ($item) {
                    return Carbon::parse($item->end_date)->format('Y-m-d');
                })
                ->addColumn('Subject', function ($item) {
                    return $item->subject;
                })
                ->addColumn('Information', function ($item) {
                    return $item->information;
                })
                ->addColumn('Status', function ($item) {
                    return $item->status;
                })
                ->addColumn('Action', function ($item) {
                    $button = '-';
                    $button_approve = ' <button onclick="updateStatusVacation(\'' . $item->uuid . '\', \'' . encrypt(Vacation::STATUS_ACCEPTED) . '\')" class="btn btn-success btn-sm">Approve</button>';
                    $button_reject = ' <button onclick="updateStatusVacation(\'' . $item->uuid . '\', \'' . encrypt(Vacation::STATUS_REJECTED) . '\')" class="btn btn-danger btn-sm">Reject</button>';
                    $button_cancel = ' <button onclick="updateStatusVacation(\'' . $item->uuid . '\', \'' . encrypt(Vacation::STATUS_CANCELED) . '\')" class="btn btn-danger btn-sm">Cancel</button>';
                    if ($item->status != Vacation::STATUS_CANCELED) {
                        if (Auth::user()->getRoleNames()->first() == 'admin') {
                            if ($item->status == Vacation::STATUS_PENDING) {
                                $button = $button_approve . $button_reject;
                            }
                            else {
                                $button = $button_cancel;
                            }
                        }
                        if ($item->status == Vacation::STATUS_PENDING || Auth::user()->getRoleNames()->first() != 'admin') {
                            $button =  $button_cancel;
                        }
                    }
                    return $button;
                })
                ->rawColumns(['Action'])
                ->make(true);

            return $dataTable;
        }
    }

    /**
     * Store a newly created vacation request.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'date' => 'required',
                'subject' => 'required',
                'information' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'msg' => 'Validation Error', 'errors' => $validator->errors()], 400);
            }

            $user = Auth::user();

            $vacation = new Vacation();
            $vacation->employee_id = $user->employee->id;

            [$startDate, $endDate] = explode(' - ', $request->date);
            $vacation->start_date = Carbon::parse($startDate);
            $vacation->end_date = Carbon::parse($endDate);

            $vacation->subject = $request->subject;
            $vacation->information = $request->information;
            $vacation->status = Vacation::STATUS_PENDING;

            $vacation->save();

            return response()->json(['status' => 'success', 'msg' => 'Vacation request submitted successfully.'], 201);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'msg' => 'Failed to submit vacation request', 'err' => $e->getMessage()], 500);
        }
    }


    public function updateStatus($vacation, Request $request)
    {
        try {
            // Cek Validasi Status
            $validator = Validator::make($request->all(), [
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'msg' => 'Validation Error', 'errors' => $validator->errors()], 400);
            }
            // Cek Vacation
            $vacation = Vacation::firstWhere('uuid', $vacation);
            if (!$vacation) {
                return response()->json(['status' => 'error', 'msg' => 'Vacation not found'], 404);
            }
           

            $status_decrypt = decrypt($request->get('status'));
            $status = Vacation::isValidStatus($status_decrypt);

            if (!$status) {
                return response()->json(['status' => 'error', 'msg' => 'Invalid status'], 400);
            }

            $vacation->status = $status_decrypt;
            $vacation->save();

            return response()->json(['status' => 'success', 'msg' => 'Vacation updated status successfully.']);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'msg' => 'Failed to update status vacation', 'err' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a vacation request.
     */
    public function destroy($uuid)
    {
        try {
            $vacation = Vacation::firstWhere('uuid', $uuid);
            if (!$vacation) {
                return response()->json(['status' => 'error', 'msg' => 'Vacation not found'], 404);
            }

            $vacation->delete();

            return response()->json(['status' => 'success', 'msg' => 'Vacation deleted successfully.']);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'msg' => 'Failed to delete vacation', 'err' => $e->getMessage()], 500);
        }
    }
}
