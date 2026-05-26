@extends('layouts.app')
@section('title', 'Departments')
@section('page-title', 'Departments')

@section('content')
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.departments.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Department</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Employees</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $dept)
                        <tr>
                            <td>{{ $dept->id }}</td>
                            <td class="fw-semibold">{{ $dept->name }}</td>
                            <td class="small text-muted">{{ $dept->description ?? '-' }}</td>
                            <td><span class="badge bg-primary">{{ $dept->users_count }}</span></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.departments.edit', $dept) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('admin.departments.destroy', $dept) }}" method="POST" onsubmit="return confirm('Delete this department?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">No departments yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $departments->links() }}</div>
@endsection
