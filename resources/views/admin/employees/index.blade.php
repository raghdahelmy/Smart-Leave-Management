@extends('layouts.app')
@section('title', 'Employees')
@section('page-title', 'Employees')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <form class="d-flex gap-2" method="GET">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search name, email, ID..." value="{{ request('search') }}">
            <select name="department_id" class="form-select form-select-sm" style="width:180px">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                @endforeach
            </select>
            <button class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
        </form>
        <a href="{{ route('admin.employees.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Employee</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Emp ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $emp)
                        <tr>
                            <td><span class="badge bg-light text-dark">{{ $emp->employee_id }}</span></td>
                            <td class="fw-semibold">{{ $emp->name }}</td>
                            <td class="small">{{ $emp->email }}</td>
                            <td>{{ $emp->department->name ?? '-' }}</td>
                            <td>{{ $emp->phone ?? '-' }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.employees.edit', $emp) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#resetModal{{ $emp->id }}" title="Reset Password"><i class="bi bi-key"></i></button>
                                    <form action="{{ route('admin.employees.destroy', $emp) }}" method="POST" onsubmit="return confirm('Delete this employee?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>

                                {{-- Reset Password Modal --}}
                                <div class="modal fade" id="resetModal{{ $emp->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-sm">
                                        <form action="{{ route('admin.employees.reset-password', $emp) }}" method="POST" class="modal-content">
                                            @csrf
                                            <div class="modal-header">
                                                <h6 class="modal-title">Reset Password</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="small text-muted mb-2">Reset password for <strong>{{ $emp->name }}</strong></p>
                                                <input type="password" name="password" class="form-control" placeholder="New password" required minlength="6">
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-sm btn-warning">Reset</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">No employees found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $employees->links() }}</div>
@endsection
