@extends('layouts.app')
@section('title', 'My Complaints')
@section('page-title', 'My Complaints')

@section('content')
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('employee.complaints.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>New Complaint</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Category</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($complaints as $complaint)
                        <tr>
                            <td>{{ $complaint->id }}</td>
                            <td><span class="badge bg-light text-dark">{{ \App\Models\Complaint::CATEGORIES[$complaint->category] ?? $complaint->category }}</span></td>
                            <td>{{ Str::limit($complaint->subject, 40) }}</td>
                            <td><span class="badge badge-{{ str_replace('_', '-', $complaint->status) }}">{{ ucfirst(str_replace('_', ' ', $complaint->status)) }}</span></td>
                            <td class="small text-muted">{{ $complaint->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('employee.complaints.show', $complaint) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">No complaints yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $complaints->links() }}</div>
@endsection
