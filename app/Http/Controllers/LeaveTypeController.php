<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    public function index()
    {
        $leaveTypes = LeaveType::withCount('leaveRequests')->latest()->paginate(15);
        return view('admin.leave-types.index', compact('leaveTypes'));
    }

    public function create()
    {
        return view('admin.leave-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'code'         => 'required|string|max:10|unique:leave_types,code',
            'default_days' => 'required|integer|min:0',
        ]);

        LeaveType::create([
            'name'         => $request->name,
            'code'         => strtoupper($request->code),
            'default_days' => $request->default_days,
            'is_active'    => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.leave-types.index')->with('success', 'Leave type created successfully.');
    }

    public function edit(LeaveType $leaveType)
    {
        return view('admin.leave-types.edit', compact('leaveType'));
    }

    public function update(Request $request, LeaveType $leaveType)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'code'         => 'required|string|max:10|unique:leave_types,code,' . $leaveType->id,
            'default_days' => 'required|integer|min:0',
        ]);

        $leaveType->update([
            'name'         => $request->name,
            'code'         => strtoupper($request->code),
            'default_days' => $request->default_days,
            'is_active'    => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.leave-types.index')->with('success', 'Leave type updated successfully.');
    }

    public function destroy(LeaveType $leaveType)
    {
        if ($leaveType->leaveRequests()->count() > 0) {
            return back()->with('error', 'Cannot delete leave type with existing requests.');
        }

        $leaveType->delete();
        return redirect()->route('admin.leave-types.index')->with('success', 'Leave type deleted successfully.');
    }
}
