<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful.',
            'user' => $user->only('id', 'name', 'email', 'role', 'employee_id'),
            'token' => $token,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect.'], 422);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return response()->json(['message' => 'Password reset successfully.']);
    }

    public function applyLeave(Request $request)
    {
        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
        ]);

        $startDate = \Carbon\Carbon::parse($request->start_date);
        $endDate = \Carbon\Carbon::parse($request->end_date);
        $totalDays = $startDate->diffInDays($endDate) + 1;

        $balance = LeaveBalance::where('user_id', $request->user()->id)
            ->where('leave_type_id', $request->leave_type_id)
            ->where('year', $startDate->year)
            ->first();

        if ($balance && $balance->remaining_days < $totalDays) {
            return response()->json(['message' => 'Insufficient leave balance. Remaining: ' . $balance->remaining_days . ' days.'], 422);
        }

        $leave = LeaveRequest::create([
            'user_id' => $request->user()->id,
            'leave_type_id' => $request->leave_type_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_days' => $totalDays,
            'reason' => $request->reason,
        ]);

        return response()->json(['message' => 'Leave request submitted.', 'data' => $leave], 201);
    }

    public function leaveHistory(Request $request)
    {
        $leaves = LeaveRequest::with('leaveType')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(15);

        return response()->json($leaves);
    }

    public function leaveStatus($id, Request $request)
    {
        $leave = LeaveRequest::with('leaveType', 'reviewer')
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json($leave);
    }

    public function createComplaint(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
        ]);

        $complaint = Complaint::create([
            'user_id' => $request->user()->id,
            'subject' => $request->subject,
            'description' => $request->description,
        ]);

        return response()->json(['message' => 'Complaint submitted.', 'data' => $complaint], 201);
    }

    public function complaintStatus($id, Request $request)
    {
        $complaint = Complaint::where('user_id', $request->user()->id)->findOrFail($id);
        return response()->json($complaint);
    }

    public function leaveBalance(Request $request)
    {
        $balances = LeaveBalance::with('leaveType')
            ->where('user_id', $request->user()->id)
            ->where('year', now()->year)
            ->get();

        return response()->json($balances);
    }
    
}
