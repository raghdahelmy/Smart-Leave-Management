<?php

namespace App\Http\Controllers;

use App\Models\LeaveBalance;
use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveBalanceController extends Controller
{
    // ─── Admin: View all balances ────────────────────────────
    public function index(Request $request)
    {
        $query = LeaveBalance::with('user', 'leaveType')
            ->where('year', $request->get('year', now()->year));

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('leave_type_id')) {
            $query->where('leave_type_id', $request->leave_type_id);
        }

        $balances = $query->latest()->paginate(20)->withQueryString();
        $employees = User::where('role', 'employee')->get();
        $leaveTypes = LeaveType::where('is_active', true)->get();

        return view('admin.balances.index', compact('balances', 'employees', 'leaveTypes'));
    }

    // ─── Admin: Add balance to individual employee ───────────
    public function addIndividual(Request $request)
    {
        $request->validate([
            'user_id'       => 'required|exists:users,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'days'          => 'required|integer|min:1',
        ]);

        $year = $request->get('year', now()->year);

        $balance = LeaveBalance::firstOrCreate(
            [
                'user_id'       => $request->user_id,
                'leave_type_id' => $request->leave_type_id,
                'year'          => $year,
            ],
            [
                'total_days'     => 0,
                'used_days'      => 0,
                'remaining_days' => 0,
            ]
        );

        $balance->increment('total_days', $request->days);
        $balance->increment('remaining_days', $request->days);

        return redirect()->route('admin.balances.index')->with('success', 'Balance added successfully.');
    }

    // ─── Admin: Add balance to ALL employees ─────────────────
    public function addToAll(Request $request)
    {
        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'days'          => 'required|integer|min:1',
        ]);

        $year = $request->get('year', now()->year);
        $employees = User::where('role', 'employee')->get();

        foreach ($employees as $employee) {
            $balance = LeaveBalance::firstOrCreate(
                [
                    'user_id'       => $employee->id,
                    'leave_type_id' => $request->leave_type_id,
                    'year'          => $year,
                ],
                [
                    'total_days'     => 0,
                    'used_days'      => 0,
                    'remaining_days' => 0,
                ]
            );

            $balance->increment('total_days', $request->days);
            $balance->increment('remaining_days', $request->days);
        }

        return redirect()->route('admin.balances.index')->with('success', 'Balance added to all employees.');
    }

    // ─── Employee: My balances ───────────────────────────────
    public function employeeBalances()
    {
        $balances = LeaveBalance::with('leaveType')
            ->where('user_id', Auth::id())
            ->where('year', now()->year)
            ->get();

        return view('employee.balances', compact('balances'));
    }
}
