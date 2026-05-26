<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\LeaveBalance;
use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('department')->where('role', 'employee');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $employees = $query->latest()->paginate(15)->withQueryString();
        $departments = Department::all();

        return view('admin.employees.index', compact('employees', 'departments'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('admin.employees.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|string|min:6',
            'phone'         => 'nullable|string|max:20',
            'department_id' => 'nullable|exists:departments,id',
            'employee_id'   => 'required|string|unique:users,employee_id',
        ]);

        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'role'          => 'employee',
            'phone'         => $request->phone,
            'department_id' => $request->department_id,
            'employee_id'   => $request->employee_id,
        ]);

        // Auto-assign default leave balances
        $leaveTypes = LeaveType::where('is_active', true)->get();
        foreach ($leaveTypes as $type) {
            LeaveBalance::create([
                'user_id'        => $user->id,
                'leave_type_id'  => $type->id,
                'total_days'     => $type->default_days,
                'used_days'      => 0,
                'remaining_days' => $type->default_days,
                'year'           => now()->year,
            ]);
        }

        ActivityLogController::log('Created employee: ' . $user->name, 'User', $user->id);

        return redirect()->route('admin.employees.index')->with('success', 'Employee created successfully.');
    }

    public function edit(User $employee)
    {
        $departments = Department::all();
        return view('admin.employees.edit', compact('employee', 'departments'));
    }

    public function update(Request $request, User $employee)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $employee->id,
            'phone'         => 'nullable|string|max:20',
            'department_id' => 'nullable|exists:departments,id',
            'employee_id'   => 'required|string|unique:users,employee_id,' . $employee->id,
        ]);

        $employee->update($request->only('name', 'email', 'phone', 'department_id', 'employee_id'));

        ActivityLogController::log('Updated employee: ' . $employee->name, 'User', $employee->id);

        return redirect()->route('admin.employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(User $employee)
    {
        ActivityLogController::log('Deleted employee: ' . $employee->name, 'User', $employee->id);
        $employee->delete();
        return redirect()->route('admin.employees.index')->with('success', 'Employee deleted successfully.');
    }

    public function resetPassword(Request $request, User $employee)
    {
        $request->validate(['password' => 'required|string|min:6']);
        $employee->update(['password' => Hash::make($request->password)]);
        ActivityLogController::log('Reset password for: ' . $employee->name, 'User', $employee->id);
        return redirect()->route('admin.employees.index')->with('success', 'Password reset successfully.');
    }
}
