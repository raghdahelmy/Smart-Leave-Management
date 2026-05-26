<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LeaveRequestController extends Controller
{
    // ─── Admin: List all leave requests ──────────────────────
    public function adminIndex(Request $request)
    {
        $query = LeaveRequest::with('user.department', 'leaveType', 'reviewer');

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

        $leaves = $query->latest()->paginate(15)->withQueryString();
        $leaveTypes = LeaveType::all();
        $employees = User::where('role', 'employee')->get();
        $departments = Department::all();

        return view('admin.leaves.index', compact('leaves', 'leaveTypes', 'employees', 'departments'));
    }

    public function adminShow(LeaveRequest $leave)
    {
        $leave->load('user', 'leaveType', 'reviewer');
        return view('admin.leaves.show', compact('leave'));
    }

    // ─── Admin: Approve / Reject ─────────────────────────────
    public function approve(Request $request, LeaveRequest $leave)
    {
        if ($leave->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        $leave->update([
            'status'      => 'approved',
            'admin_note'  => $request->admin_note,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        // Deduct from balance
        $balance = LeaveBalance::where('user_id', $leave->user_id)
            ->where('leave_type_id', $leave->leave_type_id)
            ->where('year', $leave->start_date->year)
            ->first();

        if ($balance) {
            $balance->increment('used_days', $leave->total_days);
            $balance->decrement('remaining_days', $leave->total_days);
        }

        // Notify employee
        Notification::create([
            'user_id' => $leave->user_id,
            'title'   => 'Leave Approved',
            'message' => 'Your ' . $leave->leaveType->name . ' request from ' . $leave->start_date->format('d M Y') . ' to ' . $leave->end_date->format('d M Y') . ' has been approved.',
        ]);

        ActivityLogController::log('Approved leave request #' . $leave->id . ' for ' . $leave->user->name, 'LeaveRequest', $leave->id);

        return redirect()->route('admin.leaves.index')->with('success', 'Leave request approved.');
    }

    public function reject(Request $request, LeaveRequest $leave)
    {
        if ($leave->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        $request->validate(['admin_note' => 'required|string|max:500']);

        $leave->update([
            'status'      => 'rejected',
            'admin_note'  => $request->admin_note,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        Notification::create([
            'user_id' => $leave->user_id,
            'title'   => 'Leave Rejected',
            'message' => 'Your ' . $leave->leaveType->name . ' request has been rejected. Reason: ' . $request->admin_note,
        ]);

        ActivityLogController::log('Rejected leave request #' . $leave->id . ' for ' . $leave->user->name, 'LeaveRequest', $leave->id);

        return redirect()->route('admin.leaves.index')->with('success', 'Leave request rejected.');
    }

    // ─── Employee: My Leaves ─────────────────────────────────
    public function employeeIndex(Request $request)
    {
        $query = LeaveRequest::with('leaveType')
            ->where('user_id', Auth::id());

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $leaves = $query->latest()->paginate(15)->withQueryString();
        return view('employee.leaves.index', compact('leaves'));
    }

    public function employeeCreate()
    {
        $leaveTypes = LeaveType::where('is_active', true)->get();
        $balances = LeaveBalance::with('leaveType')
            ->where('user_id', Auth::id())
            ->where('year', now()->year)
            ->get()
            ->keyBy('leave_type_id');

        return view('employee.leaves.create', compact('leaveTypes', 'balances'));
    }

    public function employeeStore(Request $request)
    {
        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date'    => 'required|date|after_or_equal:today',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'reason'        => 'required|string|max:1000',
            'document'      => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate   = Carbon::parse($request->end_date);
        $totalDays = $startDate->diffInDays($endDate) + 1;

        // Check balance
        $balance = LeaveBalance::where('user_id', Auth::id())
            ->where('leave_type_id', $request->leave_type_id)
            ->where('year', $startDate->year)
            ->first();

        if ($balance && $balance->remaining_days < $totalDays) {
            return back()->withInput()->with('error', 'Insufficient leave balance. Remaining: ' . $balance->remaining_days . ' days.');
        }

        $documentPath = null;
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('leave-documents', 'public');
        }

        $leave = LeaveRequest::create([
            'user_id'       => Auth::id(),
            'leave_type_id' => $request->leave_type_id,
            'start_date'    => $request->start_date,
            'end_date'      => $request->end_date,
            'total_days'    => $totalDays,
            'reason'        => $request->reason,
            'document'      => $documentPath,
        ]);

        ActivityLogController::log('Applied for leave (' . $totalDays . ' days)', 'LeaveRequest', $leave->id);

        return redirect()->route('employee.leaves.index')->with('success', 'Leave request submitted successfully.');
    }

    public function employeeShow(LeaveRequest $leave)
    {
        if ($leave->user_id !== Auth::id()) {
            abort(403);
        }
        $leave->load('leaveType', 'reviewer');
        return view('employee.leaves.show', compact('leave'));
    }

    // ─── Calendar (Admin) ────────────────────────────────────
    public function calendar()
    {
        $leaves = LeaveRequest::with('user', 'leaveType')
            ->where('status', 'approved')
            ->whereYear('start_date', now()->year)
            ->get()
            ->map(function ($leave) {
                return [
                    'title' => $leave->user->name . ' - ' . $leave->leaveType->name,
                    'start' => $leave->start_date->format('Y-m-d'),
                    'end'   => $leave->end_date->addDay()->format('Y-m-d'),
                    'color' => match($leave->leaveType->code) {
                        'SL' => '#ef4444', 'CL' => '#3b82f6', 'EL' => '#10b981',
                        'ML' => '#8b5cf6', 'PL' => '#f59e0b', 'EML' => '#ec4899',
                        default => '#6366f1',
                    },
                ];
            });

        return view('admin.calendar', compact('leaves'));
    }
}
