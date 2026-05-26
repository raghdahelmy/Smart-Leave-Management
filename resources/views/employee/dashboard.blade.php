@extends('layouts.app')
@section('title', 'Employee Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="icon-box bg-warning bg-opacity-10 text-warning"><i class="bi bi-hourglass-split"></i></div>
                    <div>
                        <div class="text-muted small">Pending</div>
                        <div class="fw-bold fs-5">{{ $stats['pending'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="icon-box bg-success bg-opacity-10 text-success"><i class="bi bi-check-circle-fill"></i></div>
                    <div>
                        <div class="text-muted small">Approved</div>
                        <div class="fw-bold fs-5">{{ $stats['approved'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="icon-box bg-danger bg-opacity-10 text-danger"><i class="bi bi-x-circle-fill"></i></div>
                    <div>
                        <div class="text-muted small">Rejected</div>
                        <div class="fw-bold fs-5">{{ $stats['rejected'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Leave Balances --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">My Leave Balance ({{ now()->year }})</h6>
                    <a href="{{ route('employee.balances') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Type</th>
                                <th>Total</th>
                                <th>Used</th>
                                <th>Left</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($balances as $balance)
                                <tr>
                                    <td>{{ $balance->leaveType->name }}</td>
                                    <td>{{ $balance->total_days }}</td>
                                    <td class="text-danger">{{ $balance->used_days }}</td>
                                    <td class="text-success fw-bold">{{ $balance->remaining_days }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-3">No balances assigned yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Recent Leaves --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">Recent Leave Requests</h6>
                    <a href="{{ route('employee.leaves.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>Apply</a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Type</th>
                                <th>Dates</th>
                                <th>Days</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentLeaves as $leave)
                                <tr>
                                    <td><span class="badge bg-light text-dark">{{ $leave->leaveType->code }}</span></td>
                                    <td class="small">{{ $leave->start_date->format('d M') }} - {{ $leave->end_date->format('d M Y') }}</td>
                                    <td>{{ $leave->total_days }}</td>
                                    <td><span class="badge badge-{{ $leave->status }}">{{ ucfirst($leave->status) }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-3">No leave requests yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
