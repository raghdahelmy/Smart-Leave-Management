@extends('layouts.app')
@section('title', 'Leave Types')
@section('page-title', 'Leave Types')

@section('content')
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.leave-types.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Leave Type</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Default Days</th>
                        <th>Requests</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaveTypes as $type)
                        <tr>
                            <td><span class="badge bg-primary">{{ $type->code }}</span></td>
                            <td class="fw-semibold">{{ $type->name }}</td>
                            <td>{{ $type->default_days }} days</td>
                            <td>{{ $type->leave_requests_count }}</td>
                            <td>
                                @if($type->is_active)
                                    <span class="badge badge-approved">Active</span>
                                @else
                                    <span class="badge badge-rejected">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.leave-types.edit', $type) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('admin.leave-types.destroy', $type) }}" method="POST" onsubmit="return confirm('Delete this leave type?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">No leave types yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $leaveTypes->links() }}</div>
@endsection
