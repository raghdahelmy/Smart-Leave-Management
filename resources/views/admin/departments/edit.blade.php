@extends('layouts.app')
@section('title', 'Edit Department')
@section('page-title', 'Edit Department')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.departments.update', $department) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Department Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $department->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $department->description) }}</textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Update</button>
                            <a href="{{ route('admin.departments.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
