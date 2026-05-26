<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $leaveTypes  = LeaveType::all();
        $employees   = User::where('role', 'employee')->get();
        $departments = Department::all();

        // Build filtered query
        $query = LeaveRequest::with('user.department', 'leaveType');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('leave_type_id')) {
            $query->where('leave_type_id', $request->leave_type_id);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('department_id')) {
            $query->whereHas('user', fn ($q) => $q->where('department_id', $request->department_id));
        }
        if ($request->filled('from_date')) {
            $query->whereDate('start_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('end_date', '<=', $request->to_date);
        }

        $leaves = $query->latest()->paginate(20)->withQueryString();

        return view('admin.reports.index', compact('leaves', 'leaveTypes', 'employees', 'departments'));
    }

    public function exportCsv(Request $request)
    {
        $query = LeaveRequest::with('user.department', 'leaveType');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('leave_type_id')) {
            $query->where('leave_type_id', $request->leave_type_id);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('department_id')) {
            $query->whereHas('user', fn ($q) => $q->where('department_id', $request->department_id));
        }
        if ($request->filled('from_date')) {
            $query->whereDate('start_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('end_date', '<=', $request->to_date);
        }

        $leaves = $query->latest()->get();

        $filename = 'leave_report_' . now()->format('Y_m_d_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($leaves) {
            $file = fopen('php://output', 'w');

            // CSV Header
            fputcsv($file, [
                'ID', 'Employee', 'Employee ID', 'Department', 'Leave Type',
                'Start Date', 'End Date', 'Total Days', 'Status', 'Reason',
                'Admin Note', 'Submitted At',
            ]);

            foreach ($leaves as $leave) {
                fputcsv($file, [
                    $leave->id,
                    $leave->user->name ?? '-',
                    $leave->user->employee_id ?? '-',
                    $leave->user->department->name ?? '-',
                    $leave->leaveType->name ?? '-',
                    $leave->start_date->format('Y-m-d'),
                    $leave->end_date->format('Y-m-d'),
                    $leave->total_days,
                    ucfirst($leave->status),
                    $leave->reason,
                    $leave->admin_note ?? '-',
                    $leave->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
