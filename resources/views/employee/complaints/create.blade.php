@extends('layouts.app')
@section('title', 'New Complaint')
@section('page-title', 'Submit Complaint')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('employee.complaints.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Category <span class="text-danger">*</span></label>
                            <select name="category" class="form-select" required>
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $key => $label)
                                    <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Subject <span class="text-danger">*</span></label>
                            <input type="text" name="subject" class="form-control" value="{{ old('subject') }}" required maxlength="255">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Description <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control" rows="5" required maxlength="2000">{{ old('description') }}</textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary"><i class="bi bi-send me-1"></i>Submit</button>
                            <a href="{{ route('employee.complaints.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
