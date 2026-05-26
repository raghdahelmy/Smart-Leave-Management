@extends('layouts.app')
@section('title', 'Apply Leave')
@section('page-title', 'Apply for Leave')

@section('content')
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('employee.leaves.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold small">Leave Type <span class="text-danger">*</span></label>
                                <select name="leave_type_id" class="form-select" required>
                                    <option value="">-- Select Leave Type --</option>
                                    @foreach($leaveTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }} ({{ $type->code }})
                                            @if(isset($balances[$type->id]))
                                                - {{ $balances[$type->id]->remaining_days }} days left
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Start Date <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" min="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">End Date <span class="text-danger">*</span></label>
                                <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" min="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small">Reason <span class="text-danger">*</span></label>
                                <textarea name="reason" class="form-control" rows="3" required maxlength="1000">{{ old('reason') }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small">Supporting Document</label>
                                <input type="file" name="document" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                <div class="form-text">PDF, JPG, PNG (max 2MB)</div>
                            </div>
                        </div>
                        <div class="mt-4 d-flex gap-2">
                            <button class="btn btn-primary"><i class="bi bi-send me-1"></i>Submit Request</button>
                            <a href="{{ route('employee.leaves.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Balance sidebar --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white"><h6 class="mb-0 fw-semibold">My Balance ({{ now()->year }})</h6></div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr><th>Type</th><th>Total</th><th>Used</th><th>Left</th></tr>
                        </thead>
                        <tbody>
                            @foreach($balances as $balance)
                                <tr>
                                    <td>{{ $balance->leaveType->name }}</td>
                                    <td>{{ $balance->total_days }}</td>
                                    <td class="text-danger">{{ $balance->used_days }}</td>
                                    <td class="text-success fw-bold">{{ $balance->remaining_days }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
