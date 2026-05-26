@extends('layouts.app')
@section('title', 'Edit Employee')
@section('page-title', 'Edit Employee')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.employees.update', $employee) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $employee->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Employee ID <span class="text-danger">*</span></label>
                                <input type="text" name="employee_id" class="form-control" value="{{ old('employee_id', $employee->employee_id) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $employee->email) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $employee->phone) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Department</label>
                                <select name="department_id" class="form-select">
                                    <option value="">-- Select --</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id', $employee->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-4 d-flex gap-2">
                            <button class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Update Employee</button>
                            <a href="{{ route('admin.employees.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
