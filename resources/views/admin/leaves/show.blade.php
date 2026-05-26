@extends('layouts.app')
@section('title', 'Leave Request Details')
@section('page-title', 'Leave Request Details')

@section('content')
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white"><h6 class="mb-0 fw-semibold">Request Information</h6></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-muted small">Employee</div>
                            <div class="fw-semibold">{{ $leave->user->name }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Employee ID</div>
                            <div>{{ $leave->user->employee_id ?? '-' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Leave Type</div>
                            <div><span class="badge bg-primary">{{ $leave->leaveType->name }}</span></div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Status</div>
                            <div><span class="badge badge-{{ $leave->status }} fs-6">{{ ucfirst($leave->status) }}</span></div>
                        </div>
                        <div class="col-4">
                            <div class="text-muted small">From</div>
                            <div>{{ $leave->start_date->format('d M Y') }}</div>
                        </div>
                        <div class="col-4">
                            <div class="text-muted small">To</div>
                            <div>{{ $leave->end_date->format('d M Y') }}</div>
                        </div>
                        <div class="col-4">
                            <div class="text-muted small">Total Days</div>
                            <div class="fw-bold">{{ $leave->total_days }}</div>
                        </div>
                        <div class="col-12">
                            <div class="text-muted small">Reason</div>
                            <div class="bg-light rounded p-2 mt-1">{{ $leave->reason }}</div>
                        </div>
                        @if($leave->document)
                            <div class="col-12">
                                <div class="text-muted small">Attached Document</div>
                                <a href="{{ asset('storage/' . $leave->document) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-1">
                                    <i class="bi bi-file-earmark me-1"></i>View Document
                                </a>
                            </div>
                        @endif
                        @if($leave->reviewer)
                            <div class="col-6">
                                <div class="text-muted small">Reviewed By</div>
                                <div>{{ $leave->reviewer->name }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted small">Reviewed At</div>
                                <div>{{ $leave->reviewed_at?->format('d M Y H:i') }}</div>
                            </div>
                        @endif
                        @if($leave->admin_note)
                            <div class="col-12">
                                <div class="text-muted small">Admin Note</div>
                                <div class="bg-light rounded p-2 mt-1">{{ $leave->admin_note }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if($leave->status === 'pending')
            <div class="col-lg-5">
                {{-- Approve --}}
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <form action="{{ route('admin.leaves.approve', $leave) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Note (optional)</label>
                                <textarea name="admin_note" class="form-control" rows="2" placeholder="Add a note..."></textarea>
                            </div>
                            <button class="btn btn-success w-100" onclick="return confirm('Approve this leave request?')">
                                <i class="bi bi-check-circle me-1"></i>Approve Request
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Reject --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('admin.leaves.reject', $leave) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Rejection Reason <span class="text-danger">*</span></label>
                                <textarea name="admin_note" class="form-control" rows="2" placeholder="Reason for rejection..." required></textarea>
                            </div>
                            <button class="btn btn-danger w-100" onclick="return confirm('Reject this leave request?')">
                                <i class="bi bi-x-circle me-1"></i>Reject Request
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="mt-3">
        <a href="{{ route('admin.leaves.index') }}" class="btn btn-light"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>
@endsection
