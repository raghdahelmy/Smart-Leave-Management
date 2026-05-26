@extends('layouts.app')
@section('title', 'Complaint Details')
@section('page-title', 'Complaint Details')

@section('content')
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white"><h6 class="mb-0 fw-semibold">Complaint #{{ $complaint->id }}</h6></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-muted small">Employee</div>
                            <div class="fw-semibold">{{ $complaint->user->name }}</div>
                        </div>
                        <div class="col-3">
                            <div class="text-muted small">Status</div>
                            <div><span class="badge badge-{{ str_replace('_', '-', $complaint->status) }} fs-6">{{ ucfirst(str_replace('_', ' ', $complaint->status)) }}</span></div>
                        </div>
                        <div class="col-3">
                            <div class="text-muted small">Category</div>
                            <div><span class="badge bg-light text-dark">{{ \App\Models\Complaint::CATEGORIES[$complaint->category] ?? $complaint->category }}</span></div>
                        </div>
                        <div class="col-12">
                            <div class="text-muted small">Subject</div>
                            <div class="fw-semibold">{{ $complaint->subject }}</div>
                        </div>
                        <div class="col-12">
                            <div class="text-muted small">Description</div>
                            <div class="bg-light rounded p-3 mt-1">{{ $complaint->description }}</div>
                        </div>
                        <div class="col-12">
                            <div class="text-muted small">Submitted</div>
                            <div>{{ $complaint->created_at->format('d M Y H:i') }}</div>
                        </div>
                        @if($complaint->admin_reply)
                            <div class="col-12">
                                <div class="text-muted small">Admin Reply</div>
                                <div class="bg-light rounded p-3 mt-1 border-start border-primary border-3">{{ $complaint->admin_reply }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white"><h6 class="mb-0 fw-semibold">Update Complaint</h6></div>
                <div class="card-body">
                    <form action="{{ route('admin.complaints.update', $complaint) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="open" {{ $complaint->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ $complaint->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ $complaint->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Admin Reply</label>
                            <textarea name="admin_reply" class="form-control" rows="4">{{ old('admin_reply', $complaint->admin_reply) }}</textarea>
                        </div>
                        <button class="btn btn-primary w-100"><i class="bi bi-check-lg me-1"></i>Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('admin.complaints.index') }}" class="btn btn-light"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>
@endsection
