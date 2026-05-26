@extends('layouts.app')
@section('title', 'My Leave Balance')
@section('page-title', 'My Leave Balance')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-semibold">Leave Balance - {{ now()->year }}</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Leave Type</th>
                                <th>Total Days</th>
                                <th>Used</th>
                                <th>Remaining</th>
                                <th>Usage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($balances as $balance)
                                <tr>
                                    <td class="fw-semibold">{{ $balance->leaveType->name }} <span class="text-muted">({{ $balance->leaveType->code }})</span></td>
                                    <td>{{ $balance->total_days }}</td>
                                    <td class="text-danger">{{ $balance->used_days }}</td>
                                    <td class="text-success fw-bold">{{ $balance->remaining_days }}</td>
                                    <td style="width:150px">
                                        @php $pct = $balance->total_days > 0 ? round(($balance->used_days / $balance->total_days) * 100) : 0; @endphp
                                        <div class="progress" style="height:8px">
                                            <div class="progress-bar {{ $pct > 80 ? 'bg-danger' : ($pct > 50 ? 'bg-warning' : 'bg-success') }}"
                                                 style="width:{{ $pct }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ $pct }}% used</small>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-4">No balances assigned yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
