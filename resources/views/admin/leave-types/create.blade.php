@extends('layouts.app')
@section('title', 'Add Leave Type')
@section('page-title', 'Add Leave Type')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.leave-types.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control" value="{{ old('code') }}" placeholder="CL, SL, EL..." required maxlength="10">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Default Days <span class="text-danger">*</span></label>
                            <input type="number" name="default_days" class="form-control" value="{{ old('default_days', 0) }}" min="0" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" id="isActive" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">Active</label>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Create</button>
                            <a href="{{ route('admin.leave-types.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
