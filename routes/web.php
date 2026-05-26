<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveBalanceController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// ─── Guest ───────────────────────────────────────────────────
Route::get('/', fn () => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Admin Routes ────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

    // Employees
    Route::resource('employees', EmployeeController::class)->except('show');
    Route::post('employees/{employee}/reset-password', [EmployeeController::class, 'resetPassword'])->name('employees.reset-password');

    // Departments
    Route::resource('departments', DepartmentController::class)->except('show');

    // Leave Types
    Route::resource('leave-types', LeaveTypeController::class)->except('show');

    // Leave Requests
    Route::get('leaves', [LeaveRequestController::class, 'adminIndex'])->name('leaves.index');
    Route::get('leaves/{leave}', [LeaveRequestController::class, 'adminShow'])->name('leaves.show');
    Route::post('leaves/{leave}/approve', [LeaveRequestController::class, 'approve'])->name('leaves.approve');
    Route::post('leaves/{leave}/reject', [LeaveRequestController::class, 'reject'])->name('leaves.reject');

    // Leave Balances
    Route::get('balances', [LeaveBalanceController::class, 'index'])->name('balances.index');
    Route::post('balances/add-individual', [LeaveBalanceController::class, 'addIndividual'])->name('balances.add-individual');
    Route::post('balances/add-to-all', [LeaveBalanceController::class, 'addToAll'])->name('balances.add-to-all');

    // Calendar
    Route::get('calendar', [LeaveRequestController::class, 'calendar'])->name('calendar');

    // Complaints
    Route::get('complaints', [ComplaintController::class, 'adminIndex'])->name('complaints.index');
    Route::get('complaints/{complaint}', [ComplaintController::class, 'adminShow'])->name('complaints.show');
    Route::put('complaints/{complaint}', [ComplaintController::class, 'adminUpdate'])->name('complaints.update');

    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export-csv', [ReportController::class, 'exportCsv'])->name('reports.export-csv');

    // Activity Logs
    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs');
});

// ─── Employee Routes ─────────────────────────────────────────
Route::middleware(['auth', 'role:employee'])->prefix('employee')->name('employee.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'employee'])->name('dashboard');

    // Leave Requests
    Route::get('leaves', [LeaveRequestController::class, 'employeeIndex'])->name('leaves.index');
    Route::get('leaves/create', [LeaveRequestController::class, 'employeeCreate'])->name('leaves.create');
    Route::post('leaves', [LeaveRequestController::class, 'employeeStore'])->name('leaves.store');
    Route::get('leaves/{leave}', [LeaveRequestController::class, 'employeeShow'])->name('leaves.show');

    // Balances
    Route::get('balances', [LeaveBalanceController::class, 'employeeBalances'])->name('balances');

    // Complaints
    Route::get('complaints', [ComplaintController::class, 'employeeIndex'])->name('complaints.index');
    Route::get('complaints/create', [ComplaintController::class, 'employeeCreate'])->name('complaints.create');
    Route::post('complaints', [ComplaintController::class, 'employeeStore'])->name('complaints.store');
    Route::get('complaints/{complaint}', [ComplaintController::class, 'employeeShow'])->name('complaints.show');

    // Notifications
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
});
