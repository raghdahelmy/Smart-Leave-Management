@extends('layouts.app')
@section('title', 'Leave Balances')
@section('page-title', 'Leave Balances')

@section('content')
    <div class="row g-4">
        {{-- Add Individual Balance --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white"><h6 class="mb-0 fw-semibold">Add to Individual Employee</h6></div>
                <div class="card-body">
                    <form action="{{ route('admin.balances.add-individual') }}" method="POST">
                        @csrf
                        <div class="row g-2">
                            <div class="col-12">
                                <select name="user_id" class="form-select form-select-sm" required>
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->employee_id }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-7">
                                <select name="leave_type_id" class="form-select form-select-sm" required>
                                    <option value="">Select Leave Type</option>
                                    @foreach($leaveTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3">
                                <input type="number" name="days" class="form-control form-control-sm" placeholder="Days" min="1" required>
                            </div>
                            <div class="col-2">
                                <button class="btn btn-sm btn-primary w-100">Add</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Add to All --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white"><h6 class="mb-0 fw-semibold">Add to All Employees</h6></div>
                <div class="card-body">
                    <form action="{{ route('admin.balances.add-to-all') }}" method="POST" onsubmit="return confirm('Add balance to ALL employees?')">
                        @csrf
                        <div class="row g-2">
                            <div class="col-7">
                                <select name="leave_type_id" class="form-select form-select-sm" required>
                                    <option value="">Select Leave Type</option>
                                    @foreach($leaveTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3">
                                <input type="number" name="days" class="form-control form-control-sm" placeholder="Days" min="1" required>
                            </div>
                            <div class="col-2">
                                <button class="btn btn-sm btn-warning w-100">Add All</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card border-0 shadow-sm mt-4 mb-3">
        <div class="card-body py-2">
            <form class="row g-2 align-items-end" method="GET">
                <div class="col-md-3">
                    <select name="user_id" class="form-select form-select-sm">
                        <option value="">All Employees</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ request('user_id') == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="leave_type_id" class="form-select form-select-sm">
                        <option value="">All Types</option>
                        @foreach($leaveTypes as $type)
                            <option value="{{ $type->id }}" {{ request('leave_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" name="year" class="form-control form-control-sm" placeholder="Year" value="{{ request('year', now()->year) }}">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-sm btn-primary w-100"><i class="bi bi-funnel me-1"></i>Filter</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Balance Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Employee</th>
                        <th>Leave Type</th>
                        <th>Total</th>
                        <th>Used</th>
                        <th>Remaining</th>
                        <th>Year</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($balances as $balance)
                        <tr>
                            <td class="fw-semibold">{{ $balance->user->name ?? '-' }}</td>
                            <td><span class="badge bg-light text-dark">{{ $balance->leaveType->name ?? '-' }}</span></td>
                            <td>{{ $balance->total_days }}</td>
                            <td class="text-danger">{{ $balance->used_days }}</td>
                            <td class="text-success fw-bold">{{ $balance->remaining_days }}</td>
                            <td>{{ $balance->year }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">No balances found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $balances->links() }}</div>
@endsection
