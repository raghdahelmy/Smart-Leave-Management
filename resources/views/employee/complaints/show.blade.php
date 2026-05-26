@extends('layouts.app')
@section('title', 'Complaint Details')
@section('page-title', 'Complaint Details')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-4">
                            <div class="text-muted small">Status</div>
                            <div><span class="badge badge-{{ str_replace('_', '-', $complaint->status) }} fs-6">{{ ucfirst(str_replace('_', ' ', $complaint->status)) }}</span></div>
                        </div>
                        <div class="col-4">
                            <div class="text-muted small">Category</div>
                            <div><span class="badge bg-light text-dark">{{ \App\Models\Complaint::CATEGORIES[$complaint->category] ?? $complaint->category }}</span></div>
                        </div>
                        <div class="col-4">
                            <div class="text-muted small">Submitted</div>
                            <div>{{ $complaint->created_at->format('d M Y H:i') }}</div>
                        </div>
                        <div class="col-12">
                            <div class="text-muted small">Subject</div>
                            <div class="fw-semibold">{{ $complaint->subject }}</div>
                        </div>
                        <div class="col-12">
                            <div class="text-muted small">Description</div>
                            <div class="bg-light rounded p-3 mt-1">{{ $complaint->description }}</div>
                        </div>
                        @if($complaint->admin_reply)
                            <div class="col-12">
                                <div class="text-muted small">Admin Reply</div>
                                <div class="bg-light rounded p-3 mt-1 border-start border-success border-3">{{ $complaint->admin_reply }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('employee.complaints.index') }}" class="btn btn-light"><i class="bi bi-arrow-left me-1"></i>Back</a>
            </div>
        </div>
    </div>
@endsection
