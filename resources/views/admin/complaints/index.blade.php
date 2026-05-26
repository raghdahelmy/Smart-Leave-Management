@extends('layouts.app')
@section('title', 'Complaints')
@section('page-title', 'Complaints')

@section('content')
    <div class="mb-3">
        <form class="d-flex gap-2" method="GET">
            <select name="status" class="form-select form-select-sm" style="width:150px">
                <option value="">All Status</option>
                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
            </select>
            <select name="category" class="form-select form-select-sm" style="width:170px">
                <option value="">All Categories</option>
                @foreach($categories as $key => $label)
                    <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <button class="btn btn-sm btn-primary"><i class="bi bi-funnel"></i></button>
            <a href="{{ route('admin.complaints.index') }}" class="btn btn-sm btn-light">Reset</a>
        </form>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Category</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($complaints as $complaint)
                        <tr>
                            <td>{{ $complaint->id }}</td>
                            <td class="fw-semibold">{{ $complaint->user->name }}</td>
                            <td><span class="badge bg-light text-dark">{{ \App\Models\Complaint::CATEGORIES[$complaint->category] ?? $complaint->category }}</span></td>
                            <td>{{ Str::limit($complaint->subject, 35) }}</td>
                            <td><span class="badge badge-{{ str_replace('_', '-', $complaint->status) }}">{{ ucfirst(str_replace('_', ' ', $complaint->status)) }}</span></td>
                            <td class="small text-muted">{{ $complaint->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.complaints.show', $complaint) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No complaints found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $complaints->links() }}</div>
@endsection
