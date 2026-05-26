@extends('layouts.app')
@section('title', 'My Leaves')
@section('page-title', 'My Leaves')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <form class="d-flex gap-2" method="GET">
            <select name="status" class="form-select form-select-sm" style="width:140px">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <button class="btn btn-sm btn-primary"><i class="bi bi-funnel"></i></button>
        </form>
        <a href="{{ route('employee.leaves.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>Apply Leave</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Type</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Days</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                        <tr>
                            <td><span class="badge bg-light text-dark">{{ $leave->leaveType->code }}</span></td>
                            <td class="small">{{ $leave->start_date->format('d M Y') }}</td>
                            <td class="small">{{ $leave->end_date->format('d M Y') }}</td>
                            <td>{{ $leave->total_days }}</td>
                            <td><span class="badge badge-{{ $leave->status }}">{{ ucfirst($leave->status) }}</span></td>
                            <td class="small text-muted">{{ $leave->created_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('employee.leaves.show', $leave) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No leave requests yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $leaves->links() }}</div>
@endsection
