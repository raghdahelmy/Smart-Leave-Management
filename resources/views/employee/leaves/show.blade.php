@extends('layouts.app')
@section('title', 'Leave Details')
@section('page-title', 'Leave Request Details')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row g-3">
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
                                <div class="text-muted small">Document</div>
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
                                <div class="bg-light rounded p-2 mt-1 border-start border-primary border-3">{{ $leave->admin_note }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('employee.leaves.index') }}" class="btn btn-light"><i class="bi bi-arrow-left me-1"></i>Back</a>
            </div>
        </div>
    </div>
@endsection
