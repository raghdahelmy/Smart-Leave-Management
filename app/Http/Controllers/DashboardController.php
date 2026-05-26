<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Department;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function admin()
    {
        $stats = [
            'total_employees'  => User::where('role', 'employee')->count(),
            'pending_leaves'   => LeaveRequest::where('status', 'pending')->count(),
            'approved_leaves'  => LeaveRequest::where('status', 'approved')->count(),
            'rejected_leaves'  => LeaveRequest::where('status', 'rejected')->count(),
            'total_departments'=> Department::count(),
            'open_complaints'  => Complaint::where('status', 'open')->count(),
        ];

        // Chart data: Leaves by type (for pie chart)
        $leavesByType = LeaveRequest::select('leave_type_id', DB::raw('count(*) as total'))
            ->whereYear('created_at', now()->year)
            ->groupBy('leave_type_id')
            ->with('leaveType')
            ->get()
            ->map(fn ($item) => [
                'label' => $item->leaveType->name ?? 'Unknown',
                'value' => $item->total,
            ]);

        // Chart data: Monthly leaves (for bar chart)
        $monthlyLeaves = LeaveRequest::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved'),
                DB::raw('SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected'),
                DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending')
            )
            ->whereYear('created_at', now()->year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get();

        // Chart data: Leaves by department (for bar chart)
        $leavesByDept = LeaveRequest::select('users.department_id', DB::raw('count(*) as total'))
            ->join('users', 'leave_requests.user_id', '=', 'users.id')
            ->whereYear('leave_requests.created_at', now()->year)
            ->whereNotNull('users.department_id')
            ->groupBy('users.department_id')
            ->get()
            ->map(function ($item) {
                $dept = Department::find($item->department_id);
                return [
                    'label' => $dept->name ?? 'Unknown',
                    'value' => $item->total,
                ];
            });

        $recentLeaves = LeaveRequest::with('user', 'leaveType')
            ->latest()
            ->take(5)
            ->get();

        $recentComplaints = Complaint::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'recentLeaves', 'recentComplaints',
            'leavesByType', 'monthlyLeaves', 'leavesByDept'
        ));
    }

    public function employee()
    {
        $user = Auth::user();

        $balances = LeaveBalance::with('leaveType')
            ->where('user_id', $user->id)
            ->where('year', now()->year)
            ->get();

        $recentLeaves = LeaveRequest::with('leaveType')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'pending'  => LeaveRequest::where('user_id', $user->id)->where('status', 'pending')->count(),
            'approved' => LeaveRequest::where('user_id', $user->id)->where('status', 'approved')->count(),
            'rejected' => LeaveRequest::where('user_id', $user->id)->where('status', 'rejected')->count(),
        ];

        return view('employee.dashboard', compact('balances', 'recentLeaves', 'stats'));
    }
}
